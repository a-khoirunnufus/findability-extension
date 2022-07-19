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
    $adaptiveView = $bigfile->main();

    $drive = new DriveFile();
    $staticFolders = $drive->listFilesByParent($paramFolderId, 'folder', $paramSortKey, $paramSortDir);
    $staticFiles = $drive->listFilesByParent($paramFolderId, 'file', $paramSortKey, $paramSortDir);
    $staticView = array_merge($staticFolders, $staticFiles);

    $pathToFolder = [];
    $pathToFolder[] = [
      'id' => 'root',
      'name' => 'Drive Saya',
    ];
    if($paramFolderId != 'root') {
      $pathToFolder = array_merge(
        $pathToFolder,
        $drive->getPathToFile($drive->fileHierarchy, $paramFolderId)
      );
    }

    return $this->renderPartial('index', [
      'shortcuts' => $adaptiveView,
      'pathToFolder' => $pathToFolder,
      'files' => $staticView,
      'folder_id' => $paramFolderId,
      'keyword' => $paramKeyword,
      'sort_key' => $paramSortKey,
      'sort_dir' => $paramSortDir,
    ]);
  }
}