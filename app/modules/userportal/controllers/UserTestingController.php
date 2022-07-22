<?php

namespace app\modules\userportal\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class UserTestingController extends Controller
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
          ]
        ],
      ],
    ];
  }

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionRegister()
  {
    
    return $this->render('register');
  }

  public function actionTaskDetail()
  {
    $request = \Yii::$app->request;
    $taskId = $request->get('task-id');
    
    return $this->render('task-detail');
  }
}