<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class MainController extends Controller
{
  public function actionIndex()
  {
    echo "Main Page";
  }
}