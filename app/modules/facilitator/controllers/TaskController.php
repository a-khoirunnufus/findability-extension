<?php

namespace app\modules\facilitator\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\facilitator\models\UtTask;

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

  public function actionDetail()
  {
    $pid = \Yii::$app->request->get('participant_id');
    $tid = \Yii::$app->request->get('task_id');
    
    $participant = (new \yii\db\Query())
      ->select(['id', 'name'])
      ->from('ut_participant')
      ->where(['id' => $pid])
      ->one();
    $task = UtTask::findOne($tid);

    return $this->render('detail', [
      'participant' => $participant,
      'task' => $task,
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

}