<?php

namespace quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use Google\Client;
use Google\Service\Drive;
use quicknav\components\GDriveClient;

class QuicknavController extends Controller
{
  public $enableCsrfValidation = false;

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['corsFilter'] = [
        'class' => \yii\filters\Cors::className(),
        'cors' => [
            'Origin'                           => ['https://drive.google.com'],
            'Access-Control-Request-Method'    => ['GET', 'OPTIONS'],
            'Access-Control-Request-Headers' => ['*'],
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Max-Age'           => 3600,
        ],
    ];
    $behaviors['authenticator'] = [
      'class' => HttpBearerAuth::class,
      'except' => ['options'],
    ];
    return $behaviors;
  }

  public function actionIndex()
  {
    return $this->renderPartial('index');
  }

  public function actionNavigation()
  {
    $keyword = Yii::$app->request->get('keyword');

    $client = new GDriveClient();
    $files = $client->listFiles($keyword);

    return $this->renderPartial('navigation', [
      'files' => $files,
    ]);
  }

  public function actionNavigationOld()
  {
    $keyword = Yii::$app->request->get('keyword');
    $identity = Yii::$app->user->identity;

    // get user google drive files (and folders)
    $access_token = $identity->g_access_token;
    $access_token = json_decode($access_token, true);
    $client_secret = Yii::getAlias('@app/client_secret.json');    
    $client = new Client();
    $client->setAuthConfig($client_secret);
    $client->setAccessToken($access_token);

    // only get field: id, name, parent, viewedByMeTime
    $drive = new Drive($client);
    $files = $drive->files->listFiles([
      'fields' => 'files(id,name,parents,viewedByMeTime)',
      'pageSize' => 1000,
      'q' => "name contains '$keyword' or fullText contains '$keyword'",
      // 'orderBy' => 'viewedByMeTime desc' // not supported
    ]);

    // sort $files->files by viewedByMeTime

    return $this->renderPartial('navigation', [
      'files' => $files->files,
    ]);
  }
}