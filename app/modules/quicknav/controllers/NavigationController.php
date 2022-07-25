<?php

namespace app\modules\quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\BIGFile;
use app\components\DriveFile;
use app\modules\facilitator\models\UtTaskItemLog as Log;

class NavigationController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'only' => ['index'],
        'rules' => [
          [
            'allow' => true,
            'actions' => ['index'],
            'roles' => ['@'],
          ],
        ],
        'denyCallback' => function ($rule, $action) {
          // TODO: Buat halaman untuk informasi error ini
          throw new \yii\base\UserException('Anda tidak dapat membuka halaman ini, silahkan login terlebih dahulu pada halaman user portal.');
        },
      ],
    ];
  }

  public function actionIndex()
  {
    $paramFolderId = Yii::$app->request->get('folder_id'); // parent folder currently viewed
    $paramKeyword = Yii::$app->request->get('keyword'); // NULL if not set
    if($paramKeyword === 'null') $paramKeyword = null;
    $paramSortKey = Yii::$app->request->get('sort_key'); 
    $paramSortDir = Yii::$app->request->get('sort_dir');
    $paramSortDir = intval($paramSortDir);

    $request = Yii::$app->request;
    $paramLog = $request->get('log');
    $fullUrl = $request->absoluteUrl .'?'. $request->queryString;
    if($paramLog) {
      // TODO: CASE ACTION CANCEL

      $logData = explode('@', $paramLog);
      $log = new Log;
      $log->action = $logData[0];
      $log->object = $fullUrl;
      $log->time = date('Y-m-d H:i:s', time());
      $log->task_item_id = $logData[1];
      $log->save();
    }


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
      'log' => $paramLog,
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