<?php

namespace app\modules\facilitator\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\DriveFileUt;
use app\modules\facilitator\models\UtParticipantFileStructure as FileStructure;

class ParticipantController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@'],
            'matchCallback' => function ($rule, $action) {
              return Yii::$app->user->identity->email === 'a.khoirunnufus@gmail.com';
            }
          ],
        ],
      ],
    ];
  }

  public function actionIndex()
  {
    $participants = (new \yii\db\Query())
      ->select(['id', 'name', 'age', 'job'])
      ->from('ut_participant')
      ->all();

    return $this->render('index', [
      'participants' => $participants,
    ]);
  }

  public function actionDetail()
  {
    $pId = \Yii::$app->request->get('participant_id');
    
    $participant = (new \yii\db\Query())
      ->select(['id', 'name', 'age', 'job'])
      ->from('ut_participant')
      ->where(['id' => $pId])
      ->one();
    $fileStructure = FileStructure::findOne(['participant_id' => $pId]);

    return $this->render('detail', [
      'participant' => $participant,
      'fileStructure' => $fileStructure,
    ]);
  }

  public function actionGenerateFileStructureData()
  {
    $pId = \Yii::$app->request->get('participant_id');
    $drive = new DriveFileUt($pId);
    
    // generate tree hierarchy
    $fileHierarchy = $drive->fileHierarchy;
    // $fileHierarchyJson = json_encode($fileHierarchy);
  
    // get file count at level 1,2,3,4,5,6 depth
    $filesPerDepth = [
      'level_1' => $drive->getFilesFromTreeLvOne($fileHierarchy),
      'level_2' => $drive->getFilesFromTreeLvTwo($fileHierarchy),
      'level_3' => $drive->getFilesFromTreeLvThree($fileHierarchy),
      'level_4' => $drive->getFilesFromTreeLvFour($fileHierarchy),
      'level_5' => $drive->getFilesFromTreeLvFive($fileHierarchy),
      'level_6' => $drive->getFilesFromTreeLvSix($fileHierarchy),
      'level_7' => $drive->getFilesFromTreeLvSeven($fileHierarchy),
      'level_8' => $drive->getFilesFromTreeLvEight($fileHierarchy),
    ];
    $fileCountsPerDepth = array_map(function($item) {
      return count($item);
    }, $filesPerDepth);

    // save to database
    try{
      $fileStructure = FileStructure::findOne($pId);
      if(boolval($fileStructure) == false) {
        $fileStructure = new FileStructure();
        $fileStructure->participant_id = $pId;
      }
      $fileStructure->file_hierarchy = json_encode($fileHierarchy);
      $fileStructure->files_per_depth = json_encode($filesPerDepth);
      $fileStructure->file_counts_per_depth = json_encode($fileCountsPerDepth);
      $fileStructure->save();
      Yii::$app->session->setFlash('success', 'Data telah digenerate dan berhasil disimpan.');
    } catch (\Exception $e) {
      Yii::$app->session->setFlash('failed', 'Gagal generate data.');
    }

    return $this->redirect(Yii::$app->request->referrer);
  }

  public function actionDisplayTreeView()
  {
    $pId = \Yii::$app->request->get('participant_id');
    $fileStructure = FileStructure::findOne(['participant_id' => $pId]);

    $tree = json_decode($fileStructure['file_hierarchy'], true);
    $treeHtml = $this->generateTreeHtml($tree);

    return $this->renderPartial('tree-view', [
      'html' => $treeHtml,
    ]);
  }

  // helper
  private function generateTreeHtml($tree, $level = 1)
  {
    $html = '';
    $levelClass = "level-$level";
    foreach($tree as $node) {
      if(isset($node['children'])) {
        $html .= '<li><span class="caret '.$levelClass.'">'.$node['name'].'</span>';
        $html .= '<ul class="nested">';
        $res = $this->generateTreeHtml($node['children'], $level + 1);
        $html .= $res;
        $html .= '</ul>';
      } else {
        $html .= '<li><span class="'.$levelClass.'">'.$node['name'].'</span>';
      }
      $html .= "</li>";
    }
    return $html;
  }
}  