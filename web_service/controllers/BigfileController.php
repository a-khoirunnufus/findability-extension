<?php

namespace quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use quicknav\components\BIGFile;
use Google\Client;
use Google\Service\Drive;
use yii\helpers\ArrayHelper;

class BigfileController extends Controller
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
      // 'pageSize' => 1000,
      'q' => "name contains 'networking'"
    ]);

    $bigfile = new BIGFile();
    $bigfile->targets = $files->files;

    return $this->asJson($bigfile->targets);

    $bigfile->view = null;
    $bigfile->input = null;
    $bigfile->behaviour = null;

    return 'bigfile, hello '.$identity->name;
  }
}