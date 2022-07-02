<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use Google\Client;
use Google\Service\Drive;

class FileController extends Controller
{
  public function actionViewFiles($parent_id)
  {
    $client = new Client();
    $client->setAuthConfig(Yii::getAlias('@fex/client_secret.json'));
    $client->addScope(Drive::DRIVE_METADATA_READONLY);
    $client->addScope(Drive::DRIVE_READONLY);

    $session = Yii::$app->session;
    if ($session->has('gapi_access_token')) {
      $client->setAccessToken($session->get('gapi_access_token'));
      $drive = new Drive($client);
      $files = $drive->files->listFiles(array());
      $json = json_encode($files, JSON_PRETTY_PRINT);
      return "<pre>$json</pre>";

    } else {
      // access token not exist
      return $this->redirect(Url::to(['/auth/oauth2']));
    }
  }

  public function actionFiles($parent_id)
  {
    // verify authorization header (g_token)
    // verify access_token
    // call gapi
    // process result, then return response
  }
}