<?php

namespace quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use quicknav\components\BIGFile;
use quicknav\components\DriveFile;

class QuicknavController extends Controller
{
  // public $enableCsrfValidation = false;

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
    // $behaviors['authenticator'] = [
    //   'class' => HttpBearerAuth::class,
    //   'except' => ['options'],
    // ];
    return $behaviors;
  }

  public function actionIndex()
  {
    $paramFolderId = Yii::$app->request->get('folder_id'); // parent folder currently viewed
    $paramKeyword = Yii::$app->request->get('keyword'); // NULL if not set
    if($paramKeyword === 'null') $paramKeyword = null;
    $paramSortKey = Yii::$app->request->get('sort_key'); 
    $paramSortDir = Yii::$app->request->get('sort_dir');
    $paramSortDir = intval($paramSortDir);

    $bigfile = new BIGFile($paramFolderId, $paramKeyword);
    $drive = new DriveFile();

    // shortcut data
    $adaptiveView = $bigfile->main();
    $adaptiveViewPaths = [];
    foreach($adaptiveView as $file) {
      $path = $drive->getPathToFile($bigfile->fileHierarchy, $file['id']);
      $path = $this->limitPathItem($path);
      $adaptiveViewPaths[] = $path;
    }

    // files data
    $staticFolders = $drive->listFilesByParent($paramFolderId, 'folder', $paramSortKey, $paramSortDir);
    $staticFiles = $drive->listFilesByParent($paramFolderId, 'file', $paramSortKey, $paramSortDir);
    $staticView = array_merge($staticFolders, $staticFiles);

    // breadcrumb data
    $pathToFolder = [];
    $pathToFolder[] = [
      'id' => 'root',
      'name' => 'Drive Saya',
    ];
    if($paramFolderId != 'root' and $paramFolderId != $drive->driveRootId) {
      $pathToFolder = array_merge(
        $pathToFolder,
        $drive->getPathToFile($drive->fileHierarchy, $paramFolderId)
      );
    }

    return $this->renderPartial('index', [
      'shortcuts' => $adaptiveViewPaths,
      'pathToFolder' => $pathToFolder,
      'files' => $staticView,
      'folder_id' => $paramFolderId,
      'keyword' => $paramKeyword,
      'sort_key' => $paramSortKey,
      'sort_dir' => $paramSortDir,
    ]);
  }

  private function limitPathItem($path, $limit = 3) {
    $newPath = $path;
    if(count($path) > $limit) {
      $newPath = [];
      $newPath[] = $path[0];
      $newPath[] = $path[1];
      $newPath[] = $path[count($path)-1];
    }
    return $newPath;
  }
}