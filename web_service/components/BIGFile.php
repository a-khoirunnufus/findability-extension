<?php

namespace quicknav\components;

use yii\base\BaseObject;

class BIGFile extends BaseObject {

  private $_ySets = ['select', 'back'];

  private $_targets;
  private $_view;
  private $_initialView;
  private $_input;
  // private $_behaviour;
  private $_igMax = 0;

  private $_temp_prob;

  /**
   * SETTER START
   */

  public function setTargets($value)
  {
    $this->_temp_prob = 1 / count($value);

    $targets = array_map([static::class, 'mapFiles'], $value);
    $this->_targets = $targets;
  }

  public function getTargets()
  {
    return $this->_targets;
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

  // public function setBehaviour($value)
  // {
  //   $this->_behaviour = $value;
  // }

  private function mapFiles($file)
  {
    return [
      'id' => $file['id'],
      'name' => $file['name'],
      'parents' => $file['parents'],
      'viewedByMeTime' => $file['viewedByMeTime'],
      'viewedByMeEpoch' => $file['viewedByMeEpoch'],
      'probability' => $this->_temp_prob,
    ];
  }

  /**
   * SETTER END
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