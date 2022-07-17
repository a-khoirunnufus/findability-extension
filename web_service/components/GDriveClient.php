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

  public function root()
  {
    $res = $this->_drive->files->get('root');
    $file = [
      'id' => $res->id,
      'name' => $res->name,
      'parent' => null,
      'viewedByMeTime' => null,
      'viewedByMeEpoch' => null,
    ];
    return $file;
  }

  public function file($id)
  {
    // client api not support filter by fields
    $res = $this->_drive->files->get($id);
    return $res;    
  }

  public function listFiles($size = 1000)
  {
    $res = $this->_drive->files->listFiles([
      'fields' => 'files(id,name,parents,viewedByMeTime)',
      'pageSize' => $size,
      'orderBy' => 'viewedByMeTime desc'
    ]);
    
    $files = array_map([static::class, 'mapFiles'], $res->files);

    return $files;
  }

  public function listFilesByKeyword($keyword, $size = 1000)
  {
    // Sorting is not supported for queries with fullText terms.
    $res = $this->_drive->files->listFiles([
      'fields' => 'files(id,viewedByMeTime)',
      'pageSize' => $size,
      'q' => "name contains '$keyword' or fullText contains '$keyword'",
    ]);
    
    $files = array_map(
      function($item) {
        return ['id' => $item->id, 'viewedByMeTime' => $item->viewedByMeTime];
      }, 
      $res->files
    );
    ArrayHelper::multisort($files, ['viewedByMeTime'], [SORT_DESC]);

    return $files;
  }

  public function listFilesByParent($parent_id)
  {
    $res = $this->_drive->files->listFiles([
      'fields' => 'files(id,name,parents,viewedByMeTime)',
      'pageSize' => 1000,
      'q' => "'$parent_id' in parents",
      'orderBy' => 'viewedByMeTime desc',
    ]);
    
    $files = array_map([static::class, 'mapFiles'], $res->files);
    // ArrayHelper::multisort($files, ['viewedByMeEpoch'], [SORT_DESC]);

    return $files;
  }

  private function mapFiles($file)
  {
    return [
      'id' => $file->id,
      'name' => $file->name,
      'parent' => isset($file->parents[0]) ? $file->parents[0] : null,
      'viewedByMeTime' => $file->viewedByMeTime,
    ];
  }

}