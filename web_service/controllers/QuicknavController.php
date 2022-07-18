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
    $paramFolderId = Yii::$app->request->get('folder_id'); // parent folder currently viewed
    $paramKeyword = Yii::$app->request->get('keyword'); // NULL if not set

    $bigfile = new BIGFile($paramFolderId, $paramKeyword);
    $adaptiveView = $bigfile->main();

    return $this->renderPartial('navigation', [
      'shortcuts' => $adaptiveView,
    ]);
  }

  public function actionNavigationOld()
  {
    // PARAMETERS
    $N = 4; // number of shorcut
    $n = 6; // number of leaves in tree

    $paramFolderId = Yii::$app->request->get('folder_id'); // parent folder currently viewed
    $paramKeyword = Yii::$app->request->get('keyword'); // NULL if not set
    
    $client = new GDriveClient();
    $bigfile = new BIGFile();
    $driveRoot = $client->file('root');

    $allFile = $client->listFiles();
    $allFileHierarchy = $bigfile->buildTree($allFile, $driveRoot->id);

    $rootId;
    if($paramFolderId === null) {
      // TODO: status code 401
      return 'folder_id parameter must include';
    } elseif($paramFolderId == 'root') {
      $rootId = $driveRoot->id;
    } else {
      $rootId = $paramFolderId;
    }

    // tree with root = rootId
    $fileHierarchy = [];

    if($paramFolderId == 'root') {
      $fileHierarchy = $allFileHierarchy;
    } else {
      $bigfile->getChildrenFromTree(
        $paramFolderId,     // parent id
        $allFileHierarchy,
        $fileHierarchy,     // children of node with id = parent id
      );
    }

    $files;
    $bigfile->convertTreeToArray($fileHierarchy, $files);
    ArrayHelper::multisort($files, ['viewedByMeTime'], [SORT_DESC]);
    
    // files below current root folder
    $bigfile->files = $files;
    $bigfile->targets = $files;
    
    $probableTargets;
    if ($paramKeyword) {
      $probableTargets = $client->listFilesByKeyword($paramKeyword);
    } else {
      $probableTargets = $client->listFiles(false);
    }
    
    // create minimal compressed tree
    $bigfile->compressedTargetHierarchy = [
      'targets' => $bigfile->targets, 
      'parentId' => $rootId,
      'probableTargets' => $probableTargets,
    ];
    
    $staticView = $client->listFilesByParent($rootId);
    $adaptiveView = $bigfile->main($staticView);

    return $this->renderPartial('navigation', [
      'shortcuts' => $adaptiveView,
    ]);
  }
}