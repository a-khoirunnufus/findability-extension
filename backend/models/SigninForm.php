<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * SigninForm is the model behind the Signup form.
 *
 * @property-read User|null $user
 *
 */
class SigninForm extends Model
{
  public $email;
  public $password;
  public $remember_me = true;

  public function attributeLabels()
  {
    return [
      'email' => 'Email',
      'password' => 'Password',
      'remember_me' => 'Ingat saya',
    ];
  }

  public function rules()
  {
    return [
      [['email', 'password'], 'required', 'message' => '{attribute} tidak boleh kosong.'],
      ['email', 'email', 'message' => 'Format email salah.'],
      ['remember_me', 'boolean', 'message' => 'Input salah.'],
      ['email', 'validateEmailExists'],
      ['password', 'validatePasswordCorrect'],
    ];
  }

  public function validateEmailExists($attribute, $params, $validator, $current)
  {
    if (User::findOne(['email' => $current]) === null) {
      $this->addError($attribute, 'Email ini tidak terdaftar pada sistem.');
    }
  }

  public function validatePasswordCorrect($attribute, $params, $validator, $current)
  {
    $user = User::findOne(['email' => $this->email]);
    if (! Yii::$app->getSecurity()->validatePassword($current, $user->password)) {
      $this->addError($attribute, 'Password yang anda masukkan salah.');
    }

  }

  public function signin()
  {
    if ($this->validate()) {
      $identity = User::findOne(['email' => $this->email]);
      return Yii::$app->user->login($identity, $this->remember_me ? 3600*24*30 : 0);
    }
    return false;
  }

}