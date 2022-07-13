<?php

namespace quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use quicknav\components\GDriveClient;

class FileController extends Controller
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

  public function actionListFiles()
  {
    $keyword = Yii::$app->request->get('keyword');
    $client = new GDriveClient();
    $files = $client->listFiles($keyword);

    // var_dump($files); exit;
    // $files = array_map( function($file) { return $file['id']; }, $files );
    return $this->asJson($files);
  }

  public function actionListFilesByParent()
  {
    $parent_id = Yii::$app->request->get('parent_id');
    $client = new GDriveClient();
    $files = $client->listFilesByParent($parent_id);

    // var_dump($files); exit;
    $files = array_map( function($file) { return $file['id']; }, $files );
    return $this->asJson($files);
  }
}




