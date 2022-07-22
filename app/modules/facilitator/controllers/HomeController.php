<?php

namespace app\modules\facilitator\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class HomeController extends Controller
{
  // public function behaviors()
  // {
  //   return [
  //     'access' => [
  //       'class' => AccessControl::class,
  //       'only' => ['index'],
  //       'rules' => [
  //         [
  //           'allow' => true,
  //           'actions' => ['index'],
  //           'roles' => ['@'],
  //         ],
  //       ],
  //     ],
  //   ];
  // }
  
  public function actionIndex()
  {
    return $this->render('index');
  }
}