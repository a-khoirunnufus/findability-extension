<?php

namespace fex\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
// use Google_Client;
use Google\Client;
use Google\Service\Drive;

class AuthController extends Controller
{
  public function actionSignin()
  {
    session_start();

    // Set Content Security Policy response header to prevent cross-site scripting (XSS) attack
    Yii::$app->response->headers->set('Content-Security-Policy-Report-Only', 'script-src https://accounts.google.com/gsi/client; frame-src https://accounts.google.com/gsi/; connect-src https://accounts.google.com/gsi/');

    return $this->render('signin');
  }

  public function actionSignincallback()
  {
    $request = Yii::$app->request;
    $postBody = $request->post();

    // Verify CSRF
    $csrf_cookie = Yii::$app->request->cookies->get('g_csrf_token');
    $csrf_body = $postBody['g_csrf_token'];
    if($csrf_cookie == null or $csrf_cookie != $csrf_body) {
      return "Failed to verify double submit cookie";
    }

    $client_secret = Yii::getAlias('@fex/client_secret.json');    
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

    // check access token
    // if (!$session->has('gapi_access_token')) {
    //   return $this->redirect(Url::to('@web/auth/oauth2', true));
    // }

    // $client->setAccessToken($session->get('gapi_access_token'));
    // check access token expired, if so refresh the token
    // if ($client->isAccessTokenExpired()) {
    //   return $this->redirect(Url::to('@web/auth/oauth2', true));
    // }
    
    // return $this->redirect(Url::to(['/view/files/0']));
    return $this->redirect(Url::to(['/auth/post-signin']));
  }

  public function actionSignout()
  {
    session_start();
    session_unset();
    return 'destroys all data registered to a session.';
  }

  public function actionOauth2()
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

  public function actionOauth2callback()
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