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
    // PARAMETERS
    $N = 4; // number of shorcut
    $n = 6; // number of leaves in tree

    $paramFolderId = Yii::$app->request->get('folder_id'); // parent folder currently viewed
    $paramKeyword = Yii::$app->request->get('keyword'); // NULL if not set
    
    $client = new GDriveClient();
    $bigfile = new BIGFile();

    $rootId;
    if($paramFolderId === null) {
      // TODO: status code 401
      return 'folder_id parameter must include';
    } elseif($paramFolderId == 'root') {
      $root = $client->file('root');
      $rootId = $root->id;
    } else {
      $rootId = $paramFolderId;
    }

    $files = $client->listFiles();
    $bigfile->files = $files;
    $bigfile->targets = $files;
    
    // this is probable targets, based on keyword
    $probableTargets = $client->listFilesByKeyword($paramKeyword, $n);

    // create minimal compressed tree
    $bigfile->compressedTargetHierarchy = [
      'targets' => $bigfile->targets, 
      'parentId' => $rootId,
      'probableTargets' => $probableTargets,
    ];    

    $staticView = $client->listFilesByParent($rootId);
    $adaptiveView = $bigfile->getAdaptiveView($staticView);

    return $this->renderPartial('navigation', [
      'shortcuts' => $adaptiveView,
    ]);
  }
}