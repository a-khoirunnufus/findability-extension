<?php

namespace quicknav\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use Google\Client;
use Google\Service\Drive;
use quicknav\components\GDriveClient;
use quicknav\components\BIGFile;

class QuicknavController extends Controller
{
  public $enableCsrfValidation = false;

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['corsFilter'] = [
        'class' => \yii\filters\Cors::className(),
        'cors' => [
            'Origin'                           => ['https://drive.google.com'],
            'Access-Control-Request-Method'    => ['GET', 'OPTIONS'],
            'Access-Control-Request-Headers' => ['*'],
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Max-Age'           => 3600,
        ],
    ];
    $behaviors['authenticator'] = [
      'class' => HttpBearerAuth::class,
      'except' => ['options'],
    ];
    return $behaviors;
  }

  public function actionIndex()
  {
    return $this->renderPartial('index');
  }

  public function actionNavigation()
  {
    $keyword = Yii::$app->request->get('keyword');

    $client = new GDriveClient();
    $files = $client->listFiles($keyword);
    
    $bigfile = new BIGFile();
    $bigfile->targets = $files;
    
    // index 0 - 5 -> static (root folder)
    // index 6 -> adaptive (one from keyword: networking)
    $view = [
      "1ZDUmXo7wsZIxZPvrtxmTUWVhYbLjS_js",
      "1s3ahx1P9UpyUaXcA_6_MOAOGqdJbk98f",
      "1HPkeY9eyj7RTDjiAwuDyFIqfjBLviR_3",
      "12nvO4BlOPB3qGZJ5iMhzV51nwrUjwU5M",
      "1wn1X7ClFpE7J9E5CALcDcq40JXfja8Bx",
      "1US9oId4FN2SqIC8fcl7_0O2oL-mPEVD8",
      "1Z3jUS_M4TKNX3Hv6UrML3m_WymzUGzoO", // this adaptive
    ];
    $ig = $bigfile->ig($view);

    return $this->asJson($ig);

    // return $this->renderPartial('navigation', [
    //   'files' => $files,
    // ]);
  }
}