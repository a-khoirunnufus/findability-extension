<?php

namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\User;
use Google\Client;
use Google\Service\Drive;

class DriveFileUt {
  
  public $files;
  public $fileHierarchy; 
  public $driveRootId;
  public $drive;

  public function __construct($pId) 
  {
    $identity = User::getUserByParticipantId($pId);

    $access_token = $identity->g_access_token;
    if($access_token == null) {
      // TODO: buat halaman untuk informasi ini
      throw new \yii\base\UserException('Partisipan belum memerikan izin akses google drive.');
    }

    $access_token = json_decode($access_token, true);
    $client_secret = Yii::getAlias('@app/client_secret.json');    
    $client = new Client();
    $client->setAuthConfig($client_secret);
    $client->setAccessToken($access_token);
    $this->drive = new Drive($client);
    
    $cache = Yii::$app->cache;
    $cacheKey = $identity->id.'_files_ut';
    $userFiles = $cache->get($cacheKey);
    if ($userFiles === false) {
      $userFiles['files'] = $this->fetchAllDriveFiles();
      $userFiles['driveRootId'] = $this->file('root')->id;
      $cache->set($cacheKey, $userFiles, 3600);
    }
    $this->files = $userFiles['files'];
    $this->driveRootId = $userFiles['driveRootId'];
    $this->fileHierarchy = $this->buildTree($this->files, $this->driveRootId);
  }

  public function file($id)
  {
    // client api not support filter by fields
    $res = $this->drive->files->get($id);
    return $res;    
  }

  public function fetchAllDriveFiles()
  {
    $optParams = [
      'corpora' => 'user',
      'fields' => 'nextPageToken,files(id,name,mimeType,parents,viewedByMeTime,modifiedByMeTime,size, trashed)',
      'pageSize' => 100,
      'q' => 'trashed = false',
      'orderBy' => 'viewedByMeTime desc'
    ];

    $files;
    $res = $this->drive->files->listFiles($optParams);
    $files = $res->files;
    while($res->nextPageToken) {
      $optParams['pageToken'] = $res->nextPageToken;
      $res = $this->drive->files->listFiles($optParams);
      $files = array_merge($files, $res->files);
    }

    $files = array_map(function($item) {
      return [
        'id' => $item->id,
        'name' => $item->name,
        'mimeType' => $item->mimeType,
        'parent' => isset($item->parents[0]) ? $item->parents[0] : null,
        'viewedByMeTime' => $item->viewedByMeTime,
        'modifiedByMeTime' => $item->modifiedByMeTime,
        'size' => $item->size,
      ];
    }, $files);

    return $files;
  }

  public function listFiles($includeFolder = true, $sortKey = 'viewedByMeTime', $sortDir = SORT_DESC)
  {
    $files = $this->files;
    $filteredFiles = array_filter($files, function($item) use($includeFolder){
      if($includeFolder and $item['mimeType'] == 'application/vnd.google-apps.folder') {
        return true;
      }
      if($item['mimeType'] != 'application/vnd.google-apps.folder') {
        return true;
      }
    });
    ArrayHelper::multisort($filteredFiles, $sortKey, $sortDir);
    $filteredFiles = array_slice($filteredFiles, 0, 100);

    return $filteredFiles;
  }

  public function listFilesByParent($parentId, $type, $sortKey = 'name', $sortDir = SORT_ASC)
  {
    if($parentId == 'root') $parentId = $this->driveRootId;
    $files = $this->files;
    $filteredFiles = array_filter($files, function($item) use($parentId, $type){
      if($item['parent'] == $parentId) {
        if($type == "file" and $item['mimeType'] != 'application/vnd.google-apps.folder') {
          return true;
        }
        if($type == "folder" and $item['mimeType'] == 'application/vnd.google-apps.folder') {
          return true;
        }
      }
    });
    ArrayHelper::multisort($filteredFiles, $sortKey, $sortDir);

    return $filteredFiles;
  }

  public function listFilesByKeyword($keyword)
  {
    // Sorting is not supported for queries with fullText terms.
    $res = $this->drive->files->listFiles([
      'fields' => 'files(id,viewedByMeTime)',
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

  public function getPathToFile($tree, $fileId)
  {
    foreach($tree as $file) {
      if($file['id'] == $fileId) {
        return [[
          'id' => $file['id'],
          'name' => $file['name'],
          'parent' => $file['parent'],
          'mimeType' => $file['mimeType'],
        ]];
      }
      if(isset($file['children'])) {
        $pathToFile = $this->getPathToFile($file['children'], $fileId);
        if($pathToFile) {
          $arr = [];
          array_push($arr, [
            'id' => $file['id'],
            'name' => $file['name'],
            'parent' => $file['parent'],
            'mimeType' => $file['mimeType'],
          ]);
          foreach($pathToFile as $file) {
            array_push($arr, $file);
          } 
          return $arr;
        }
      }
    }
  }

  private function buildTree(array $elements, $parentId) 
  {
    $branch = array();
    foreach ($elements as $element) {
      if ( $element['parent'] === $parentId ) {
        $children = $this->buildTree($elements, $element['id']);
        if ($children) {
          $element['children'] = $children;
        }
        $branch[] = $element;
      }
    }
    return $branch;
  }

  public function getChildrenFromTree($parentId, $tree, &$outChildren)
  {
    foreach ($tree as $node) {
      if(isset($node['children'])) {
        if ($node['id'] === $parentId) {
          $outChildren = $node['children'];
          break;
        }
        $this->getChildrenFromTree($parentId, $node['children'], $outChildren);
      }
    }
  }

  public function getFilesFromTreeLvOne($tree)
  {
    $files = [];
    foreach($tree as $nodeLvOne) { 
      if($nodeLvOne['mimeType'] !== 'application/vnd.google-apps.folder') {
        unset($nodeLvOne['children']);
        $files[] = $nodeLvOne;
      }
    }
    return $files;
  }

  public function getFilesFromTreeLvTwo($tree)
  {
    $files = [];
    foreach($tree as $nodeLvOne) { 
      if(isset($nodeLvOne['children'])) {
        foreach($nodeLvOne['children'] as $nodeLvTwo) {
          if($nodeLvTwo['mimeType'] !== 'application/vnd.google-apps.folder') {
            unset($nodeLvTwo['children']);
            $files[] = $nodeLvTwo;
          }
        }
      }
    }
    return $files;
  }

  public function getFilesFromTreeLvThree($tree)
  {
    $files = [];
    foreach($tree as $nodeLvOne) { 
      if(isset($nodeLvOne['children'])) {
        foreach($nodeLvOne['children'] as $nodeLvTwo) {
          
          if(isset($nodeLvTwo['children'])) {
            foreach($nodeLvTwo['children'] as $nodeLvThree) {
              if($nodeLvThree['mimeType'] !== 'application/vnd.google-apps.folder') {
                unset($nodeLvThree['children']);
                $files[] = $nodeLvThree;
              }
            }
          }    
        }
      }
    }
    return $files;
  }

  public function getFilesFromTreeLvFour($tree)
  {
    $files = [];
    foreach($tree as $nodeLvOne) { 
      if(isset($nodeLvOne['children'])) {
        foreach($nodeLvOne['children'] as $nodeLvTwo) {
          
          if(isset($nodeLvTwo['children'])) {
            foreach($nodeLvTwo['children'] as $nodeLvThree) {
              
              if(isset($nodeLvThree['children'])) {
                foreach($nodeLvThree['children'] as $nodeLvFour) {
                  if($nodeLvFour['mimeType'] !== 'application/vnd.google-apps.folder') {
                    unset($nodeLvFour['children']);
                    $files[] = $nodeLvFour;
                  }
                }
              }
            }
          }    
        }
      }
    }
    return $files;
  }

  public function getFilesFromTreeLvFive($tree)
  {
    $files = [];
    foreach($tree as $nodeLvOne) { 
      if(isset($nodeLvOne['children'])) {
        foreach($nodeLvOne['children'] as $nodeLvTwo) {
          
          if(isset($nodeLvTwo['children'])) {
            foreach($nodeLvTwo['children'] as $nodeLvThree) {
              
              if(isset($nodeLvThree['children'])) {
                foreach($nodeLvThree['children'] as $nodeLvFour) {
                  
                  if(isset($nodeLvFour['children'])) {
                    foreach($nodeLvFour['children'] as $nodeLvFive) {
                      if($nodeLvFive['mimeType'] !== 'application/vnd.google-apps.folder') {
                        unset($nodeLvFive['children']);
                        $files[] = $nodeLvFive;
                      }
                    }
                  }    
                }
              }
            }
          }    
        }
      }
    }
    return $files;
  }

  public function getFilesFromTreeLvSix($tree)
  {
    $files = [];
    foreach($tree as $nodeLvOne) { 
      if(isset($nodeLvOne['children'])) {
        foreach($nodeLvOne['children'] as $nodeLvTwo) {
          
          if(isset($nodeLvTwo['children'])) {
            foreach($nodeLvTwo['children'] as $nodeLvThree) {
              
              if(isset($nodeLvThree['children'])) {
                foreach($nodeLvThree['children'] as $nodeLvFour) {
                  
                  if(isset($nodeLvFour['children'])) {
                    foreach($nodeLvFour['children'] as $nodeLvFive) {
                      
                      if(isset($nodeLvFive['children'])) {
                        foreach($nodeLvFive['children'] as $nodeLvSix) {
                          if($nodeLvSix['mimeType'] !== 'application/vnd.google-apps.folder') {
                            unset($nodeLvSix['children']);
                            $files[] = $nodeLvSix;
                          }
                        }
                      }
                    }
                  }    
                }
              }
            }
          }    
        }
      }
    }
    return $files;
  }

  public function getFilesFromTreeLvSeven($tree)
  {
    $files = [];
    foreach($tree as $nodeLvOne) { 
      if(isset($nodeLvOne['children'])) {
        foreach($nodeLvOne['children'] as $nodeLvTwo) {
          
          if(isset($nodeLvTwo['children'])) {
            foreach($nodeLvTwo['children'] as $nodeLvThree) {
              
              if(isset($nodeLvThree['children'])) {
                foreach($nodeLvThree['children'] as $nodeLvFour) {
                  
                  if(isset($nodeLvFour['children'])) {
                    foreach($nodeLvFour['children'] as $nodeLvFive) {
                      
                      if(isset($nodeLvFive['children'])) {
                        foreach($nodeLvFive['children'] as $nodeLvSix) {
                          
                          if(isset($nodeLvSix['children'])) {
                            foreach($nodeLvSix['children'] as $nodeLvSeven) {
                              if($nodeLvSeven['mimeType'] !== 'application/vnd.google-apps.folder') {
                                unset($nodeLvSeven['children']);
                                $files[] = $nodeLvSeven;
                              }
                            }
                          }
                        }
                      }
                    }
                  }    
                }
              }
            }
          }    
        }
      }
    }
    return $files;
  }

  public function getFilesFromTreeLvEight($tree)
  {
    $files = [];
    foreach($tree as $nodeLvOne) { 
      if(isset($nodeLvOne['children'])) {
        foreach($nodeLvOne['children'] as $nodeLvTwo) {
          
          if(isset($nodeLvTwo['children'])) {
            foreach($nodeLvTwo['children'] as $nodeLvThree) {
              
              if(isset($nodeLvThree['children'])) {
                foreach($nodeLvThree['children'] as $nodeLvFour) {
                  
                  if(isset($nodeLvFour['children'])) {
                    foreach($nodeLvFour['children'] as $nodeLvFive) {
                      
                      if(isset($nodeLvFive['children'])) {
                        foreach($nodeLvFive['children'] as $nodeLvSix) {
                          
                          if(isset($nodeLvSix['children'])) {
                            foreach($nodeLvSix['children'] as $nodeLvSeven) {
                              
                              if(isset($nodeLvSeven['children'])) {
                                foreach($nodeLvSeven['children'] as $nodeLvEight) {
                                  if($nodeLvEight['mimeType'] !== 'application/vnd.google-apps.folder') {
                                    unset($nodeLvEight['children']);
                                    $files[] = $nodeLvEight;
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }    
                }
              }
            }
          }    
        }
      }
    }
    return $files;
  }

}