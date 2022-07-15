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
  public $_tempSumNumber = 0;


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

  public function setTargetHierarchy($value)
  {
    $targets = $value['targets'];
    $parentId = $value['parentId'];
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

  public function getViewSetFromTree($tree)
  {
    $n = 4;
    $setA = [];
    $counter = 0;
    // var_dump($tree); exit;
    while ($n > 0) {
      $length = count($tree);
      $limit = $length <= $n ? $length : $n;
      for ($i=0; $i < $limit; $i++) { 
        $setA[] = $tree[$i]['id'];
        $children = isset($tree[$i]['children']) ? $tree[$i]['children'] : null;
        unset($tree[$i]);
        if ($children) {
          foreach ($children as $file) {
            $tree[] = $file;
          }
        }
        $n--;
      }
      // reindexing array
      $tree = array_values($tree);
    }
    return [
      'setA' => $setA,
      'updatedTree' => $tree,
    ];
  }

  private function testRecursive()
  {

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

  public function getAdaptiveView($staticView)
  {
    $setA = [];
    $setAPrime = [];
    $tree = $this->_compressedTargetHierarchy;
    $treePrime = [];
    $view = [];
    $viewPrime = [];
    $ig = 0;
    $igPrime = 0;

    $staticViewIds = array_map(function($item) {
      return $item['id'];
    }, $staticView);

    // set setA and treePrime
    ['setA' => $setA, 'updatedTree' => $treePrime] = $this->getViewSetFromTree($tree);
    
    // set view
    $view = array_merge($setA, $staticViewIds);
    
    // calculate IGmax = IG(S⋃A)
    $ig = $this->ig($view);
    $igMax = $ig;

    $aMax = [];

    $stop = false;

    // empty tree means no sets to explore


    // while there are more sets to explore
    while (! $stop) {
      $prunedTree = $tree;
      $res = $this->getChildOnSet($setA, $prunedTree);
      // Node a ∈ A has child a′, and not yet explored
      if ($res) {
        $setAPrime = $setA;
        $parentIdx = array_search($res['parent'], $setAPrime);
        $setAPrime[$parentIdx] = $res['child'];

        // Compute IG′(S⋃A′)
        $viewPrime = array_merge($setAPrime, $staticViewIds);
        $igPrime = $this->ig($viewPrime);
        if ($ig > $igPrime) {
          // Skip the subtree rooted at a′ 
          $tree = $prunedTree;
        } else {
          $ig = $igPrime;
          $setA = $setAPrime;
        }
      }
      // Node a ∈ A has not child a′
      else {
        // A = A − a  + the root of the next branch
        // $setA = 
        break;
      }

      if($ig > $igMax) {
        $igMax = $ig;
        $aMax = $setA;
      }
    }

    return $aMax;

      // foreach element in $setA, check if it is have children
      // if have, replace that element with one children (of that element)
      
    //   foreach ($setA as $key => $nodeId) {
    //     $break = false;

    //     $children; $this->getChildrenByNodeId($nodeId, $tree, $children);
    //     if($children) {
    //       $setAPrime = $setA;
    //       foreach($children as $cId) {
    //         if (! in_array($cId, $setA)) {
    //           $setAPrime[$key] = $children[0];
    //           $break = true;
    //           break;
    //         }
    //       }
    //       if($break) {
    //         $viewPrime = array_merge($setAPrime, $staticViewIds);
    //         $igPrime = $this->ig($viewPrime);
    //         if ($ig > $igPrime) {
    //           // Skip the subtree rooted at a′ 
    //         } else {
    //           $ig = $igPrime;
    //           $setA = $setAPrime;
    //         }
    //         break;
    //       }
    //     }
    //   }

    //   if($ig > $igMax) {
    //     $igMax = $ig;
    //     $aMax = $setA;
    //   }

    // // }

    // return $aMax;

    //   exit;


    //   return $setA;
    //   exit;

    //     // node a in A has a child a' and not yet explored
    //     // check is it have a chilren
    //     $children = $this->getOneChildren($nodeId);
    //     if($children) {
    //       $setAPrime[$key] = $children['id'];
    //       $replacedA[] = ['parent' => $nodeId, 'children' => $children['id']];
    //     }
    //   // }

    //   // compute igPrime (using setAPrime)
    //   $viewPrime = array_merge($setAPrime, $staticViewIds);
    //   $igPrime = $this->ig($viewPrime);

    //   $newSetA = $setA;
    //   $newTree = $tree;
    //   // $ig = $this->ig($view);

    //   ['setA' => $newSetA, 'updatedTree' => $newTree] = $this->getViewSetFromTree($newTree);
    //   $staticViewIds = array_map(function($item) {
    //     return $item['id'];
    //   }, $staticView);
    //   $view = array_merge($newSetA, $staticViewIds);
    //   $igPrime = $this->ig($view);
      
    //   if ($ig > $igPrime) {
    //     // prune every node a' in A' (and delete children in these node)
    //     foreach ($newSetA as $nodeId) {
    //       $this->deleteNode($nodeId, $tree);
    //     }
    //     // convert assosiative to indexed array
    //     $tree = array_values($tree);
    //     $newTree = $tree;
    //   } else {
    //     $ig = $igPrime;
    //     $setA = $newSetA;
    //     // $tree = $newTree;
    //   }


    // }

    // var_dump([$ig, $igPrime]); exit;

  }

  public function testGetAdaptiveView($staticView)
  {
    $setA = [];
    $setAPrime = [];
    $staticViewIds = array_map(
      function($item) { return $item['id']; }, 
      $staticView
    );

    $tree = $this->_compressedTargetHierarchy;

    // initial explore
    ['set' => $setA, 'updatedTree' => $tree] 
      = $this->exploreTree($tree);
    $aMax = $setA;

    $view = array_merge($setA, $staticViewIds);
    $ig = $this->ig($view);
    $igMax = $ig;

    while (count($tree) > 0) {
      // Node a ∈ A has child a′ and not yet explored
      foreach ($setA as $key => $a) {
        if ($this->hasChildInTree($a, $tree)) {
          $setAPrime = $setA;
          // TODO: getPrimeSetWithReplacedParent()

          break; // this break is necessary
        } else {
          ['set' => $setA, 'updatedTree' => $tree] 
            = $this->getSetWithNextBranch($a, $tree);
          $view = array_merge($setA, $staticViewIds);
          $ig = $this->ig($view);

          break;
        }
      }

      if($ig > $igMax) {
        $igMax = $ig;
        $aMax = $setA;
      }
      break;
    }

    return $aMax;
  }
  
  // berguna
  private function exploreTree($tree, $n = 4)
  {    
    while (count($tree) < $n) { 
      foreach ($tree as $key => $branch) {
        if (isset($branch['children'])) {
          array_splice($tree, $key, 1, $branch['children']);
          break;
        }
      }
    }

    $set = [];
    for ($i=0; $i < $n; $i++) { 
      $set[] = $tree[$i]['id'];
    }

    return [
      'set' => $set,
      'updatedTree' => $tree,
    ];
  }

  private function hasChildInTree($a, $tree)
  {
    foreach ($tree as $node) {
      if ($node['id'] === $a and isset($node['children'])) {
        return $node['children'][0]['id'];
      }
    }
    return false;
  }

  private function getSetWithNextBranch($nodeId, $tree)
  {
    foreach($tree as $key => $node) {
      if ($node['id'] == $nodeId) {
        unset($tree[$key]);
        break;
      }
    }
    $tree = array_values($tree);
    return $this->exploreTree($tree);
  }


  private function getChildOnSet($setA, &$tree)
  {
    $res = null;
    foreach ($setA as $a) {
      $child = null;
      $this->getChildOnSetRecursive($setA, $a, $tree, $child);
      if($child) {
        $res = [
          'parent' => $a,
          'child' => $child['id'],
        ];
        break;
      }
    }
    return $res;
  }

  private function getChildOnSetRecursive($setA, $nodeId, &$tree, &$outChild)
  {
    foreach ($tree as &$node) {
      if (isset($node['children'])) {
        $this->getChildOnSetRecursive($setA, $nodeId, $node['children'], $outChild);
      }
      if ($node['id'] == $nodeId) {
        $outChild = null;
        if (isset($node['children'])) {
          $children = $node['children'];
          $child = end($children);
          if (! in_array($child['id'], $setA)) {
            $child = array_pop($children);
            if (isset($child['children'])) {
              unset($child['children']);
            }
            $children[] = $child;
            $node['children'] = $children;
            $outChild = $child;
            break;
          }
        }
      }
    }
  }

  public function getChildrenByNodeId($nodeId, $tree, &$outChildren)
  {
    foreach ($tree as $node) {
      if (isset($node['children'])) {
        $this->getChildrenByNodeId($nodeId, $node['children'], $outChildren);
      }
      if($node['id'] == $nodeId) {
        $outChildren = null;
        if (isset($node['children'])) {
          $outChildren = [];
          foreach($node['children'] as $children) {
            $outChildren[] = $children['id'];
          }
        }
        break;
      }
    }
  }

  public function exploreTreeRecursive($tree)
  {
    foreach ($tree as $node) {
      echo $node['id'] . "<br>";
      if(isset($node['children'])) {
        $this->exploreTreeRecursive($node['children']);
      }
    }
  }

  private function deleteNode($nodeId, &$tree)
  {
    foreach ($tree as $key => $node) {
      if (isset($node['children'])) {
        $this->deleteNode($nodeId, $node['children']);  
      }
      if($node['id'] == $nodeId) {
        unset($tree[$key]);
        // unset($node['children']);
        break;
      }
    }
  }

  public function searchFileFromTree($fileId, &$tree, &$fileOut)
  {
    foreach ($tree as &$node) {
      if (isset($node['children'])) {
        $this->searchFileFromTree($fileId, $node['children'], $fileOut);  
      }
      if($node['id'] == $fileId) {
        $node['explored'] = true;
        $fileOut = $node;
        break;
      }
    }
  }

}