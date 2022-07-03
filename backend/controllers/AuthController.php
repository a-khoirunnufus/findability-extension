<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use Google\Client;
use Google\Service\Drive;
use app\models\SignupForm;
use app\models\SigninForm;
use yii\filters\AccessControl;

class AuthController extends Controller
{
  public $layout = 'public';

  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'only' => ['signin', 'signout', 'signup'],
        'rules' => [
          [
            'allow' => true,
            'actions' => ['signin', 'signup'],
            'roles' => ['?'],
          ],
          [
            'allow' => true,
            'actions' => ['signout'],
            'roles' => ['@'],
          ],
        ],
      ],
    ];
  }
  
  public function beforeAction($action)
  {
      if (in_array($action->id, ['signin-callback'])) {
          $this->enableCsrfValidation = false;
      }
      return parent::beforeAction($action);
  }

  public function actionSignup()
  {
    // Set Content Security Policy response header to prevent cross-site scripting (XSS) attack
    // Yii::$app->response->headers->set('Content-Security-Policy-Report-Only', 'script-src https://accounts.google.com/gsi/client; frame-src https://accounts.google.com/gsi/; connect-src https://accounts.google.com/gsi/');
    
    $model = new SignupForm();
    if (Yii::$app->request->isPost) {
      $model->load(Yii::$app->request->post());
      if ($model->signup()) {
        // return $this->render('post_signup');
        return $this->render('post_verification');
      }
    }

    $model->password = '';
    $model->password_repeat = '';
    return $this->render('signup', [
      'model' => $model
    ]);
  }

  public function actionSignin()
  {
    // Set Content Security Policy response header to prevent cross-site scripting (XSS) attack
    // Yii::$app->response->headers->set('Content-Security-Policy-Report-Only', 'script-src https://accounts.google.com/gsi/client; frame-src https://accounts.google.com/gsi/; connect-src https://accounts.google.com/gsi/');
    
    $model = new SigninForm();
    if (Yii::$app->request->isPost) {
      $model->load(Yii::$app->request->post());
      if ($model->signin()) {
        return $this->redirect(Url::toRoute('main/index'));
      }
    }

    $model->password = '';
    return $this->render('signin', [
      'model' => $model
    ]);
  }

  public function actionSigninCallback()
  {
    $request = Yii::$app->request;
    $postBody = $request->post();

    // Verify CSRF
    // $csrf_cookie = Yii::$app->request->cookies->get('g_csrf_token');
    $csrf_cookie = $_COOKIE['g_csrf_token'];
    $csrf_body = $postBody['g_csrf_token'];
    if($csrf_cookie == null or $csrf_cookie != $csrf_body) {
      return "Failed to verify double submit cookie, $csrf_cookie, $csrf_body";
    }

    $client_secret = Yii::getAlias('@app/client_secret.json');    
    $client = new Client();
    $client->setAuthConfig($client_secret);

    // Verify ID Token
    $id_token = $postBody['credential'];
    $payload = $client->verifyIdToken($id_token);
    if (! boolval($payload)) {
      return "Invalid ID Token";
    }

    $cookies = Yii::$app->response->cookies;
    $cookies->add(new \yii\web\Cookie([
      'name' => 'g_token',
      'value' => $id_token,
      'httpOnly' => false
    ]));

    // check access token in database
    // check token expiration
    // if expire refresh token
    
    return $this->redirect(Url::to(['/auth/post-signin']));
  }

  public function actionSignout()
  {
    Yii::$app->user->logout();

    return $this->redirect(Url::to(['signin']));
  }

  public function actionOauth()
  {
    $email = Yii::$app->session->get('email');
    $redirect_uri = Url::to('@web/auth/oauth2callback', true);
    $client_secret = Yii::getAlias('@fex/client_secret.json');
    
    $client = new Client();
    $client->setAuthConfig($client_secret);
    $client->setRedirectUri($redirect_uri);
    $client->addScope(Drive::DRIVE_METADATA_READONLY);
    $client->addScope(Drive::DRIVE_READONLY);
    $client->setLoginHint($email);

    // redirect to OAuth2 server
    $request = Yii::$app->request;
    $auth_url = $client->createAuthUrl();
    return $this->redirect($auth_url);
  }

  public function actionOauthCallback()
  {
    $redirect_uri = Url::to('@web/auth/oauth2callback', true);
    $client_secret = Yii::getAlias('@fex/client_secret.json');

    $client = new Client();
    $client->setAuthConfig($client_secret);
    $client->setRedirectUri($redirect_uri);
    $client->addScope(Drive::DRIVE_METADATA_READONLY);
    $client->addScope(Drive::DRIVE_READONLY);

    // exchange access token
    $request = Yii::$app->request;
    $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));
    Yii::$app->session->set('gapi_access_token', $token);

    // return $this->redirect(Url::to(['/view/files/0']));
    return $this->render('post_signin');
  }

  public function actionPostSignin()
  {
    return $this->render('post_signin');
  }
}