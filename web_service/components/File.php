<?php

namespace quicknav\components;

use Yii;
use yii\helpers\ArrayHelper;
use Google\Client;
use Google\Service\Drive;

class File {
  
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

    $this->_drive = new Drive($client);
  }

  public function listFilesByParent($parentId, $type = 'file', $sort = 'name asc')
  {
    $optParams = [
      'fields' => 'files(id,name,modifiedByMeTime,size)',
      'pageSize' => 1000,
      'q' => "'$parentId' in parents",
      'orderBy' => $sort,
    ];
    if($type == 'file') {
      $optParams['q'] = "'$parentId' in parents and mimeType != 'application/vnd.google-apps.folder'";
    } elseif ($type == 'folder') {
      $optParams['q'] = "'$parentId' in parents and mimeType = 'application/vnd.google-apps.folder'";
    }

    $res = $this->_drive->files->listFiles($optParams);
    
    $files = array_map(function($item) {
      return [
        'id' => $item->id,
        'name' => $item->name,
        'modifiedTime' => date('j M Y', strtotime($item->modifiedByMeTime)),
        'size' => floor($item->size / 1000),
      ];
    }, $res->files);

    return $files;
  }

  function mapFiles($file)
  {
    return [
      'id' => $file->id,
      'name' => $file->name,
      'parent' => isset($file->parents[0]) ? $file->parents[0] : null,
      
    ];
  }
}