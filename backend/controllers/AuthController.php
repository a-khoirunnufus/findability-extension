<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use Google\Client;
use Google\Service\Drive;
use app\models\User;
use yii\filters\AccessControl;

class AuthController extends Controller
{
  public $layout = 'public';

  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'only' => ['logout', 'add-google-drive-access'],
        'rules' => [
          [
            'allow' => true,
            'actions' => ['logout', 'add-google-drive-access'],
            'roles' => ['@'],
          ],
        ],
      ],
    ];
  }
  
  public function beforeAction($action)
  {
      if (in_array($action->id, ['signin-with-google-callback'])) {
          $this->enableCsrfValidation = false;
      }
      return parent::beforeAction($action);
  }

  public function actionLogin()
  {
    // Set Content Security Policy response header to prevent cross-site scripting (XSS) attack
    Yii::$app->response->headers->set(
      'Content-Security-Policy-Report-Only', 
      "script-src 'self' https://accounts.google.com/gsi/client 'unsafe-inline';"
      ." frame-src 'self' https://accounts.google.com/gsi/;"
      ." connect-src 'self' https://accounts.google.com/gsi/"
    );
    
    return $this->render('login');
  }

  public function actionSigninWithGoogleCallback()
  {
    $request = Yii::$app->request;
    $postBody = $request->post();

    // Verify CSRF
    $csrf_cookie = $_COOKIE['g_csrf_token'];
    $csrf_body = $postBody['g_csrf_token'];
    if($csrf_cookie == null or $csrf_cookie != $csrf_body) {
      Yii::$app->session->setFlash('notification.type', 'error');
      Yii::$app->session->setFlash('notification.message', 'Terjadi kesalahan saat ingin membuat anda masuk ke sistem.');
      return $this->render('signin_failed');
    }

    $client_secret = Yii::getAlias('@app/client_secret.json');    
    $client = new Client();
    $client->setAuthConfig($client_secret);

    // Verify ID Token
    $id_token = $postBody['credential'];
    $payload = $client->verifyIdToken($id_token);
    if (! boolval($payload)) {
      Yii::$app->session->setFlash('notification.type', 'error');
      Yii::$app->session->setFlash('notification.message', 'Terjadi kesalahan saat ingin membuat anda masuk ke sistem.'); 
      return $this->render('signin_failed');
    }
    
    $user = User::findOne(['email' => $payload['email']]);

    if(! boolval($user)) {
      try {
        $res = User::registerWithGoogleAccount($payload);
        if ($res) {
          Yii::$app->session->setFlash('notification.type', 'success');
          Yii::$app->session->setFlash('notification.message', 'Akun anda berhasil dibuat, selamat datang '.$payload['name'].'.');
        }
      } catch (\yii\db\Exception $e) {
        Yii::$app->session->setFlash('notification.type', 'error');
        Yii::$app->session->setFlash('notification.message', 'Terjadi kesalahan saat ingin membuat anda masuk ke sistem.');
        return $this->render('signin_failed');  
      }
    }

    // login a user
    $identity = User::findOne(['email' => $payload['email']]);
    Yii::$app->user->login($identity, 3600*24*3); // session expired after 3 days

    // redirect to home/index
    return $this->redirect(Url::toRoute('home/index'));
  }

  public function actionLogout()
  {
    Yii::$app->user->logout();

    return $this->redirect(Url::toRoute(['auth/login']));
  }

  public function actionOauth2Callback()
  {
    $client_secret = Yii::getAlias('@app/client_secret.json');

    $client = new Client();
    $client->setAuthConfig($client_secret);

    // exchange access token
    $request = Yii::$app->request;
    $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

    $email = Yii::$app->user->identity->email;
    $user = User::findOne(['email' => $email]);
    $user->g_access_token = $token['access_token'];
    $user->g_access_token_created_at = date('Y-m-d H:i:s', $token['created']);
    $user->g_refresh_token = $token['refresh_token'];
    $user->save();

    Yii::$app->session->setFlash('notification.type', 'success');
    Yii::$app->session->setFlash('notification.message', 'Akses ke google drive telah diizinkan.');

    return $this->redirect(Url::toRoute('profile/index'));
  }

  public function actionAddGoogleDriveAccess()
  {
    $email = Yii::$app->user->identity->email;
    $redirect_uri = Url::toRoute('auth/oauth2-callback', true);
    $client_secret = Yii::getAlias('@app/client_secret.json');
    
    $client = new Client();
    $client->setAuthConfig($client_secret);
    $client->setRedirectUri($redirect_uri);
    $client->addScope(Drive::DRIVE_METADATA_READONLY);
    $client->addScope(Drive::DRIVE_READONLY);
    $client->setAccessType('offline');
    $client->setLoginHint($email);

    // redirect to OAuth2 server
    $auth_url = $client->createAuthUrl();
    return $this->redirect($auth_url);
  }
}