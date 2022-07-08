<?php

namespace quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;

class FileController extends Controller
{
  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
      'class' => HttpBearerAuth::class,
    ];
    return $behaviors;
  }

  public function actionIndex()
  {
    return 'Hello '.Yii::$app->user->identity->name;
  }
}




