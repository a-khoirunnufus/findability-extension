<?php

namespace quicknav\components;

use yii\base\BaseObject;

class BIGFile extends BaseObject {

  private $_targets;
  private $_view;
  private $_input;
  private $_behaviour;
  private $_igmax = 0;

  /**
   * SETTER START
   */

  public function setTargets($value)
  {
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

  public function setInput($value)
  {
    $this->_input = $value;
  }

  public function setBehaviour($value)
  {
    $this->_behaviour = $value;
  }

  private function mapFiles($file)
  {
    $probability = pow( 1/2, 0.00001 * (time() - strtotime($file->viewedByMeTime)) );

    return [
      'id' => $file->id,
      'name' => $file->name,
      'parents' => $file->parents,
      'probability' => number_format($probability, 4, '.', ''),
    ];
  }

  /**
   * SETTER END
   */

  

}