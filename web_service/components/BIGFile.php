<?php

namespace quicknav\components;

use yii\base\BaseObject;

class BIGFile extends BaseObject {

  private $_files;
  private $_fileHierarchy;
  private $_targets;
  private $_targetHierarchy;
  private $_compressedTargetHierarchy;
  private $_probableTargetIds;
  
  private $_ySets = ['select', 'back'];
  private $_view;
  private $_initialView;
  private $_input;
  private $_igMax = 0;

  private $_tempSumProbability = 0;

  /**
   * GETTER & SETTER START
   */

  public function setTargets($value)
  {
    $targets = array_map(function ($item) {
      $probability = pow( 1/2, 0.00001 * ( time() - strtotime($item['viewedByMeTime']) ) );
      $this->_tempSumProbability += $probability;
      return [
        'id' => $item['id'],
        'parent' => $item['parent'],
        'probability' => $probability,
      ];
    }, $value);

    // normalize probability
    $targetsNormalize = array_map(function ($item) {
      $newProbability = round($item['probability'] / $this->_tempSumProbability, 4);
      return [
        'id' => $item['id'],
        'parent' => $item['parent'],
        'probability' => $newProbability,
      ];
    }, $targets);

    $this->_targets = $targetsNormalize;
  }

  public function getTargets()
  {
    return $this->_targets;
  }

  public function setTargetHierarchy($targets, $parentId)
  {
    $this->_targetHierarchy = $this->buildTree($targets, $parentId);
  }

  public function getTargetHierarchy()
  {
    return $this->_targetHierarchy;
  }

  public function setProbableTargetIds($value)
  {
    $ids = array_map(function($item) {
      return $item['id'];
    }, $value);
    $this->_probableTargetIds = $ids;
  }

  public function getProbableTargetIds()
  {
    return $this->_probableTargetIds;
  }

  public function setCompressedTargetHierarchy($value)
  {
    $targets = $value['targets'];
    $parentId = $value['parentId'];

    // mark target
    $targetsMarked = array_map(function($item) {
      $item['selectedTarget'] = false;
      if (in_array($item['id'], $this->_probableTargetIds)) {
        $item['selectedTarget'] = true;
      }
      return $item;
    }, $targets);
    
    // build tree
    $tree = $this->buildTree($targetsMarked, $parentId);

    // build compressed tree
    $this->_compressedTargetHierarchy = $this->buildCompressedTree($tree);
  }

  public function getCompressedTargetHierarchy()
  {
    return $this->_compressedTargetHierarchy;
  }

  public function setView($value)
  {
    $this->_view = $value;
  }

  public function setInitialView($value)
  {
    $this->_initialView = $value;
  }

  public function setInput($value)
  {
    $this->_input = $value;
  }

  public function setFiles($value)
  {
    $this->_files = $value;
  }

  public function getFiles()
  {
    return $this->_files;
  }

  public function setFileHierarchy($files, $parentId)
  {
    $this->_fileHierarchy = $this->buildTree($files, $parentId);
  }

  public function getFileHierarchy($files, $parentId)
  {
    return $this->_fileHierarchy;
  }

  /**
   * GETTER & SETTER END
   */


  /**
   * SETTER UTILITY START
   */

  private function buildTree(array $elements, $parentId) {
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

  private function buildCompressedTree($elements) 
  {
    $branch = [];
    foreach ($elements as $element) {
      if ($element['selectedTarget']) {
        unset($element['selectedTarget']);
        $branch[] = $element;
      }
      if (isset($element['children'])) {
        $children = $this->buildCompressedTree($element['children']);
        if (count($children) === 1) {
          $branch[] = $children[0];
        }
        elseif (count($children) > 1) {
          $element['children'] = $children;
          unset($element['selectedTarget']);
          $branch[] = $element;
        }
      }
    }
    return $branch;
  }

  /**
   * SETTER UTILITY END
   */

  
  /**
   * MAIN LOGIC
   */

   /**
   * Implementation of P(Y=y∣Θ=θ,X=x)
   * 
   * @param string $y user input
   * @param string $t target id / file id
   * @param array $x array of file id in a view
   */
  public function ubProb($y, $t, $x)
  {
    switch ($y) {
      case 'select':
        if (in_array($t, $x)) {
          return 0.95;
        }
        return 0.05;

      case 'back':
        if (!in_array($t, $x)) {
          return 0.95;
        }
        return 0.05;
    }
  }

  /**
   * Compute IG(S⋃A)
   * I(Θ;Y∣X(S⋃A)) = H(Y|X=x) − H(Y|Θ,X=x)
   * First Term H(Y|X=x) = ∑_y P(Y=y∣X=x) log2 P(Y=y∣X=x)
   * Second Term H(Y|Θ,X=x) = ∑_y,0 P(Θ=θ)P(Y=y∣Θ=θ,X=x) log2 P(Y=y∣Θ=θ,X=x)
   */
  public function ig($view)
  {
    $first_term = 0;
    $second_term = 0;

    // (1) ∑_y P(Y=y∣X=x) log2 P(Y=y∣X=x)
    $sum1 = 0;
    foreach($this->_ySets as $input)
    {
      // (1.1) P(Y=y∣X=x)
      $p11 = 0;
      // P(Y=y∣X=x) = ∑_θ′ (1.1.1) P(Y=y∣Θ=θ′,X=x) (1.1.2) P(Θ=θ′)
      $sum11 = 0;
      foreach($this->_targets as $target)
      {
        // (1.1.1) P(Y=y∣Θ=θ′,X=x)
        // todo: check for this $t param format
        $p111 = $this->ubProb($input, $target['id'], $view);
        // (1.1.2) P(Θ=θ′)
        $p112 = $target['probability'];
        $sum11 += $p111 * $p112;
      }
      $p11 = $sum11;
      // (1.2) log_2 P(Y=y∣X=x)
      $p12 = 0;
      if($p11 > 0) {
        $p12 = log($p11, 2);
      }
      $sum1 += $p11 * $p12;
    }
    $first_term = $sum1;

    // (2) ∑_y,θ (2.1) P(Θ=θ) (2.2) P(Y=y∣Θ=θ,X=x) (2.3) log_2 P(Y=y∣Θ=θ,X=x)
    $sum2a = 0; // sum of y (input)
    foreach($this->_ySets as $input)
    {
      $sum2b = 0; // sum of θ (target)
      foreach($this->_targets as $target)
      {
        // (2.1) P(Θ=θ)
        $p21 = $target['probability'];
        // (2.2) P(Y=y∣Θ=θ,X=S⋃A)
        $p22 = $this->ubProb($input, $target['id'], $view);
        // (2.3) log_2 P(Y=y∣Θ=θ,X=S⋃A)
        $p23 = 0;
        if($p22 > 0) {
          $p23 = log($p22, 2);
        }
        $sum2b += $p21 * $p22 * $p23;
      }
      $sum2a += $sum2b;
    }
    $second_term = $sum2a;

    $ig = $first_term - $second_term;
    if($ig < 0) $ig *= -1; // always positive

    return $ig;
  }

  public function getAMax()
  {
    $targetMax = null;

    // for all combination of A on N notes in targets
    // and that are below the current folder
    // $this->_initialView; // static view
    // $this->_targets is already sorted by viewedByMeTime Descending

    foreach($this->_targets as $target) {
      $view = array_merge([$target['id']], $this->_initialView);
      $ig = $this->ig($view);
      if ($ig > $this->_igMax) {
        $this->_igMax = $ig;
        $targetMax = $target;
      }
    }

    return $targetMax;
  }

  

}