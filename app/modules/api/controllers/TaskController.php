<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBasicAuth;
use app\modules\facilitator\models\UtParticipant as Participant;
use app\modules\facilitator\models\UtTask as Task;

class TaskController extends Controller
{
  public function behaviors()
  {
      $behaviors = parent::behaviors();
      $behaviors['authenticator'] = [
          'class' => HttpBasicAuth::class,
      ];
      return $behaviors;
  }
  
  public function actionIndex()
  {
    $identity = Yii::$app->user->identity;
    $participant = Participant::findOne(['user_id' => $identity->id]);

    $task = Task::find()
      ->where(['participant_id' => $participant['id']])
      ->orderBy('order ASC')
      ->asArray()
      ->all();

    return $this->asJson([
      'task' => $task
    ]);
  }
}