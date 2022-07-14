<?php

namespace quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use Google\Client;
use Google\Service\Drive;
use quicknav\components\GDriveClient;
use quicknav\components\BIGFile;

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
    $keyword = Yii::$app->request->get('keyword'); // NULL if not set
    $client = new GDriveClient();
    $bigfile = new BIGFile();
    $root = $client->root();

    $files = $client->listFiles();
    
    // $bigfile->files = array_merge([$root], $files);
    // $bigfile->files = $files;
    // $fileHierarchy = $bigfile->setFileHierarchy($files, $root['id']);
    
    $bigfile->targets = $files;
    $bigfile->setTargetHierarchy($bigfile->targets, $root['id']); // $bigfile->targets already mapped

    // this is probable targets, based on keyword
    $bigfile->probableTargetIds = $client->listFilesByKeyword($keyword);

    // create minimal compressed tree
    $bigfile->compressedTargetHierarchy = [
      'targets' => $bigfile->targets, 
      'parentId' => $root['id']
    ];
    
    return $this->asJson($bigfile->compressedTargetHierarchy);

    
    exit;
    
    // files at root folder
    $staticView = $client->listFilesByParent($root['id']);
    $staticView = [
      "1ZDUmXo7wsZIxZPvrtxmTUWVhYbLjS_js",
      "1s3ahx1P9UpyUaXcA_6_MOAOGqdJbk98f",
      "1HPkeY9eyj7RTDjiAwuDyFIqfjBLviR_3",
      "12nvO4BlOPB3qGZJ5iMhzV51nwrUjwU5M",
      "1wn1X7ClFpE7J9E5CALcDcq40JXfja8Bx",
      "1US9oId4FN2SqIC8fcl7_0O2oL-mPEVD8",
    ];

    $bigfile->initialView = $staticView;
    $shortcut = $bigfile->getAMax();
    // var_dump($shortcut); exit;

    return $this->renderPartial('navigation', [
      'shortcut' => $shortcut,
    ]);
  }
}