<?php

namespace app\modules\userportal\controllers;

use Yii;
use yii\web\Controller;

class HomeController extends Controller
{
  public function actionIndex()
  {
    return 'hello from userportal module';
  }
}