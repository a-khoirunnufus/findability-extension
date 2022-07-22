<?php

namespace app\modules\userportal\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\User;
use app\modules\facilitator\models\UtParticipant;

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
    $identity = \Yii::$app->user->identity;
    $isParticipant = User::isUTParticipant($identity->id);
    if($isParticipant) {
      return $this->render('index');
    } else {
      return $this->redirect(Url::toRoute('user-testing/register', true));
    }
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
  
  /**
   * Resource related actions
   */

  public function actionAddParticipant()
  {
    $postData = Yii::$app->request->post();
    
    // try{
      $utParticipat = new UtParticipant();
      $utParticipat->user_id = $postData['user_id'];
      $utParticipat->name = $postData['name'];
      $utParticipat->age = $postData['age'];
      $utParticipat->job = $postData['job'];
      $utParticipat->save();
      Yii::$app->session->setFlash('success', 'Berhasil mendaftar sebagai partisipan.');
    // } catch (\Exception $e) {
    //   Yii::$app->session->setFlash('failed', 'Gagal mendaftar sebagai partisipan.');
    // }

    return $this->redirect(Url::toRoute('user-testing/index', true));
  }
  
}