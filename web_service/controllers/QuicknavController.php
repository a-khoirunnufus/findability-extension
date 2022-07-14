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
    // $bigfile->targetHierarchy = [
    //   'targets' => $bigfile->targets, 
    //   'parentId' => $root['id']
    // ];

    // this is probable targets, based on keyword
    $bigfile->probableTargetIds = $client->listFilesByKeyword($keyword);

    // create minimal compressed tree
    $bigfile->compressedTargetHierarchy = [
      'targets' => $bigfile->targets, 
      'parentId' => $root['id']
    ];

    // testing start
    $bigfile->searchFileFromTree("11qbc9W6uNMAxCwjadIWZpuTjS1LndJ9D", $bigfile->compressedTargetHierarchy); 
    // testing end
    
    $staticView = $client->listFilesByParent($root['id']);
    $adaptiveView = $bigfile->getAdaptiveView($staticView); 

    exit;
    
    // files at root folder

    return $this->renderPartial('navigation', [
      'shortcut' => $shortcut,
    ]);
  }
}