<?php

namespace app\modules\facilitator\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\facilitator\models\UtTask;
use app\components\DriveFileUt;
use app\modules\facilitator\models\UtTaskTarget;

class TaskController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@'],
            'matchCallback' => function ($rule, $action) {
              return Yii::$app->user->identity->email === 'a.khoirunnufus@gmail.com';
            }
          ],
        ],
      ],
    ];
  }
  
  /**
   * Page related actions
   */

  public function actionSelectParticipant()
  {
    $participants = (new \yii\db\Query())
      ->select(['id', 'name', 'age', 'job'])
      ->from('ut_participant')
      ->all();

    return $this->render('select-participant', [
      'participants' => $participants,
    ]);
  }

  public function actionList()
  {
    $pid = \Yii::$app->request->get('participant_id');
    $participant = (new \yii\db\Query())
      ->select(['id', 'name'])
      ->from('ut_participant')
      ->where(['id' => $pid])
      ->one();
    $tasks = (new \yii\db\Query())
      ->select(['id', 'code', 'name', 'order'])
      ->from('ut_task')
      ->where(['participant_id' => $pid])
      ->orderBy(['order' => SORT_ASC])
      ->all();
    
    return $this->render('list', [
      'participant' => $participant,
      'tasks' => $tasks,
    ]);
  }

  public function actionSetup()
  {
    $pId = \Yii::$app->request->get('participant_id');
    $tId = \Yii::$app->request->get('task_id');
    $drive = new DriveFileUt($pId);
    
    $participant = (new \yii\db\Query())
      ->select(['id', 'name'])
      ->from('ut_participant')
      ->where(['id' => $pId])
      ->one();
    $task = UtTask::findOne($tId);

    $numberOfFiles = $drive->numberOfFiles;
    $filesPerDepth = $drive->filesPerDepth;
    $fileCountsPerDepth = $drive->fileCountsPerDepth;

    // selected files 1
    $cache = Yii::$app->cache;
    $cacheKey = $pId.'_ut_selected_files_1';
    $selectedFiles1 = $cache->get($cacheKey);
    $selectedFiles1Arr = [];
    if($selectedFiles1) {
      foreach($selectedFiles1 as $fileId) {
        $res = $drive->getFileById($fileId);
        if($res) {
          $pathToFile = $drive->getPathToFile($drive->fileHierarchy, $fileId);
          $res['depth'] = count($pathToFile);
          $res['pathToFile'] = implode("/", $pathToFile);
          $selectedFiles1Arr[] = $res;
        }
      }
    }

    // final targets
    $targets = UtTaskTarget::find()
      ->where(['task_id' => $tId])
      ->all();
    $targetsExtended = [];
    foreach($targets as $item) {
      $file = $drive->getFileById($item['file_id']);
      $newTarget['name'] = $file['name'];
      $newTarget['depth'] = $item['file_depth'];
      $newTarget['path_to_file'] = $item['path_to_file'];
      $newTarget['viewed_by_me_time'] = $file['viewedByMeTime'];
      $newTarget['frequency'] = $item['frequency'];
      $targetsExtended[] = $newTarget;
    }

    return $this->render('setup', [
      'participant' => $participant,
      'numberOfFiles' => $numberOfFiles,
      'filesPerDepth' => $filesPerDepth,
      'fileCountsPerDepth' => $fileCountsPerDepth,
      'selectedFiles1' => $selectedFiles1Arr,
      'task' => $task,
      'targets' => $targetsExtended,
    ]);
  }

  /**
   * Resource related actions
   */

  public function actionAddTask()
  {
    $postData = \Yii::$app->request->post();

    try{
      $utTask = new UtTask();
      $utTask->code = $postData['code'];
      $utTask->name = $postData['name'];
      $utTask->order = $postData['order'];
      $utTask->participant_id = $postData['participant_id'];
      $utTask->save();
      Yii::$app->session->setFlash('success', 'Tugas berhasil ditambahkan.');
    } catch (\Exception $e) {
      Yii::$app->session->setFlash('failed', 'Tugas gagal ditambahkan.');
    }
    
    return $this->redirect(Yii::$app->request->referrer);
  }

  public function actionUpdateTask()
  {
    $postData = \Yii::$app->request->post();

    try{
      $utTask = UtTask::findOne($postData['task_id']);
      $utTask->code = $postData['code'];
      $utTask->name = $postData['name'];
      $utTask->order = $postData['order'];
      $utTask->participant_id = $postData['participant_id'];
      $utTask->save();
      Yii::$app->session->setFlash('success', 'Tugas berhasil diupdate.');
    } catch (\Exception $e) {
      Yii::$app->session->setFlash('failed', 'Tugas gagal diupdate.');
    }
    
    return $this->redirect(Yii::$app->request->referrer);
  }

  public function actionDeleteTask()
  {
    $taskId = \Yii::$app->request->post('task_id');

    try{
      $utTask = UtTask::findOne($taskId);
      $utTask->delete();
      Yii::$app->session->setFlash('success', 'Tugas berhasil dihapus.');
    } catch (\Exception $e) {
      Yii::$app->session->setFlash('failed', 'Tugas gagal dihapus.');
    }
    
    return $this->redirect(Yii::$app->request->referrer);
  }

  public function actionLoadDefaultTask()
  {
    $pId = \Yii::$app->request->post('participant_id');

    $defaultTasks = (new \yii\db\Query())
      ->select(['code', 'name', 'order'])
      ->from('ut_task_ref')
      ->orderBy(['order' => SORT_ASC])
      ->all();

    $transaction = UtTask::getDb()->beginTransaction();
    try {
      foreach($defaultTasks as $task) {
        $utTask = new UtTask();
        $utTask->code = $task['code'];
        $utTask->name = $task['name'];
        $utTask->order = $task['order'];
        $utTask->participant_id = $pId;
        $utTask->save();
      }
      $transaction->commit();
      Yii::$app->session->setFlash('success', 'Tugas berhasil diload.');
    } catch(\Exception $e) {
      $transaction->rollBack();
      Yii::$app->session->setFlash('failed', 'Tugas gagal diload.');
    } catch(\Throwable $e) {
      $transaction->rollBack();
      Yii::$app->session->setFlash('failed', 'Tugas gagal diload.');
    }
    
    return $this->redirect(Yii::$app->request->referrer);
  }

  public function actionSelectFile1()
  {
    $pId = \Yii::$app->request->post('participant_id');
    $selectedFiles = \Yii::$app->request->post('selected_files');
    $selectedFileIds = explode(',', $selectedFiles);
      
    // save to cache
    $cache = Yii::$app->cache;
    $cacheKey = $pId.'_ut_selected_files_1';
    $cache->set($cacheKey, $selectedFileIds, 3600);

    // redirect back
    return $this->redirect(Yii::$app->request->referrer);
  }

  public function actionSetFinalTargets()
  {
    $tId = \Yii::$app->request->post('task_id');
    $pId = \Yii::$app->request->post('participant_id');
    $finalTargetsString = \Yii::$app->request->post('final_targets');
    $drive = new DriveFileUt($pId);

    $finalTargetsRaw = explode(',', $finalTargetsString);
    $finalTargets = [];
    foreach($finalTargetsRaw as $targetString) {
      $target = explode('@', $targetString);
      $finalTargets[] = [
        'fileId' => $target[0],
        'frequency' => intval($target[1]),
      ];
    }

    $transaction = UtTaskTarget::getDb()->beginTransaction();
    try {
      // save to db
      foreach($finalTargets as $item) {
        $target = new UtTaskTarget();
        $target->file_id = $item['fileId'];
  
        $pathToFile = $drive->getPathToFile($drive->fileHierarchy, $item['fileId']);
        $target->path_to_file = implode(" > ", $pathToFile);
        $target->file_depth = count($pathToFile);
        
        $target->frequency = $item['frequency'];
        $target->task_id = $tId;
        $target->save();
      }
      $transaction->commit();
      Yii::$app->session->setFlash('success', 'Target berhasil disimpan.');
    } catch(\Exception $e) {
      $transaction->rollBack();
      Yii::$app->session->setFlash('failed', 'Target gagal disimpan.');
    } catch(\Throwable $e) {
      $transaction->rollBack();
      Yii::$app->session->setFlash('failed', 'Target gagal disimpan.');
    }

    return $this->redirect(Yii::$app->request->referrer);
  }

}