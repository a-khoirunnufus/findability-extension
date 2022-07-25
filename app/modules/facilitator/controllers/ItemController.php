<?php

namespace app\modules\facilitator\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\facilitator\models\UtTask as Task;
use app\components\DriveFileUt as Drive;
use app\modules\facilitator\models\UtTaskItem as Item;
use app\modules\facilitator\models\UtTaskItemLog as Log;
use app\modules\facilitator\models\UtTaskItemLogFinal as LogFinal;

class ItemController extends Controller
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

  public function actionIndex()
  {
    $request = Yii::$app->request;
    $paramParticipantId = $request->get('participant_id');
    $participant = (new \yii\db\Query())
      ->select(['id', 'name'])
      ->from('ut_participant')
      ->where(['id' => $paramParticipantId])
      ->one();

    $paramTaskId = $request->get('task_id');
    $task = Task::findOne(intval($paramTaskId));

    $items = Item::find()
      ->where(['task_id' => intval($paramTaskId)])
      ->orderBy('order ASC')
      ->all();

    return $this->render('index', [
      'participant' => $participant,
      'task' => $task,
      'items' => $items,
    ]);
  }

  public function actionValidation()
  {
    $request = Yii::$app->request;
    $paramParticipantId = $request->get('participant_id');
    $participant = (new \yii\db\Query())
      ->select(['id', 'name'])
      ->from('ut_participant')
      ->where(['id' => $paramParticipantId])
      ->one();

    $paramTaskId = $request->get('task_id');
    $task = Task::findOne(intval($paramTaskId));
    
    $paramTaskItemId = $request->get('task_item_id');
    $item = Item::findOne(intval($paramTaskItemId));

    $logs = Log::find()
      ->where(['task_item_id' => $paramTaskItemId])
      ->orderBy('id ASC')
      ->all();

    return $this->render('validation', [
      'participant' => $participant,
      'task' => $task,
      'item' => $item,
      'logs' => $logs,
    ]);
  }

  public function actionValidate()
  {
    $request = Yii::$app->request;
    $taskItemId = $request->post('task_item_id');
    $taskItemLogIds = $request->post('task_item_log_id');

    $transaction = LogFinal::getDb()->beginTransaction();
    try {
      LogFinal::deleteAll(['task_item_id' => $taskItemId]);

      foreach($taskItemLogIds as $taskItemLogId) {
        $log = Log::findOne(intval($taskItemLogId));
        $logFinal = new LogFinal();
        $logFinal->task_item_id = $taskItemId;
        $logFinal->task_item_log_id = $taskItemLogId;
        $logFinal->time = $log->time;
        $logFinal->save();
      }

      $item = Item::findOne($taskItemId);
      $item->status = 'COMPLETED';
      $item->save();
      
      $transaction->commit();
      Yii::$app->session->setFlash('success', 'Berhasil menyimpan data.');
    } catch(\Exception $e) {
      $transaction->rollBack();
      Yii::$app->session->setFlash('failed', 'Gagal menyimpan data.');
    } catch(\Throwable $e) {
      $transaction->rollBack();
      Yii::$app->session->setFlash('failed', 'Gagal menyimpan data.');
    }
    
    return $this->redirect(Yii::$app->request->referrer);
  }

}