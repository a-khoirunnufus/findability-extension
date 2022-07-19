<?php

namespace quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use quicknav\components\DriveFile;

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
    // $behaviors['authenticator'] = [
    //   'class' => HttpBearerAuth::class,
    //   'except' => ['options'],
    // ];
    return $behaviors;
  }

  public function actionFile()
  {
    $paramFolderId = Yii::$app->request->get('folder_id');
    $fileObj = new DriveFile();
    $folders = $fileObj->listFilesByParent($paramFolderId, 'folder');
    $files = $fileObj->listFilesByParent($paramFolderId, 'file');
    $data = array_merge($folders, $files);    
    
    return $this->asJson($data);
  }

  public function actionTest()
  {
    $fileId = Yii::$app->request->get('file_id');
    $fileObj = new DriveFile();
    $res = $fileObj->getPathToFile($fileObj->fileHierarchy, $fileId);
    return $this->asJson($res);
  }
}




