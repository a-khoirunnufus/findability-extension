<?php

namespace quicknav\components;

use Yii;
use yii\base\BaseObject;
use Google\Client;
use Google\Service\Drive;
use yii\helpers\ArrayHelper;

class GDriveClient {
  
  private $_client;
  private $_drive;

  public function __construct() 
  {
    $identity = Yii::$app->user->identity;

    // get user google drive files (and folders)
    $access_token = $identity->g_access_token;
    $access_token = json_decode($access_token, true);
    $client_secret = Yii::getAlias('@app/client_secret.json');    
    $client = new Client();
    $client->setAuthConfig($client_secret);
    $client->setAccessToken($access_token);

    $this->_client = $client;
    $this->_drive = new Drive($client);
  }

  public function listFiles($keyword)
  {
    $res = $this->_drive->files->listFiles([
      'fields' => 'files(id,name,parents,viewedByMeTime)',
      'pageSize' => 1000,
      'q' => "name contains '$keyword' or fullText contains '$keyword'",
    ]);
    
    $files = array_map([static::class, 'mapFiles'], $res->files);
    ArrayHelper::multisort($files, ['viewedByMeEpoch'], [SORT_DESC]);

    return $files;
  }

  private function mapFiles($file)
  {
    return [
      'id' => $file->id,
      'name' => $file->name,
      'parents' => $file->parents,
      'viewedByMeTime' => $file->viewedByMeTime,
      'viewedByMeEpoch' => strtotime($file->viewedByMeTime),
    ];
  }

}