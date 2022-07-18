<?php

namespace quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use Google\Client;
use Google\Service\Drive;
use quicknav\components\GDriveClient;
use quicknav\components\BIGFile;
use quicknav\components\File;

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
    $paramFolderId = Yii::$app->request->get('folder_id'); // parent folder currently viewed
    $paramKeyword = Yii::$app->request->get('keyword'); // NULL if not set

    $bigfile = new BIGFile($paramFolderId, $paramKeyword);
    $adaptiveView = $bigfile->main();

    // $file = new File();
    // $staticView = $file->getFilesByFolder($paramFolderId);

    return $this->renderPartial('navigation', [
      'shortcuts' => $adaptiveView,
    ]);
  }
}