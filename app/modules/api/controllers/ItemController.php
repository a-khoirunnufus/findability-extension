<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBasicAuth;
use app\modules\facilitator\models\UtParticipant as Participant;
use app\modules\facilitator\models\UtTaskItem as Item;

class ItemController extends Controller
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
    $request = Yii::$app->request;
    $taskId = $request->get('task_id');

    $taskItems = Item::find()
      ->where(['task_id' => $taskId])
      ->orderBy('order ASC')
      ->asArray()
      ->all();

    return $this->asJson([
      'taskItems' => $taskItems,
    ]);
  }

  public function actionDetail()
  {
    $request = Yii::$app->request;
    $itemId = $request->get('item_id');

    $taskItem = Item::findOne($itemId);

    return $this->asJson([
      'taskItem' => $taskItem,
    ]);
  }
}