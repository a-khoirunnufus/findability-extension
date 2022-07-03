<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * SignupForm is the model behind the Signup form.
 *
 * @property-read User|null $user
 *
 */
class SignupForm extends Model
{
  public $name;
  public $email;
  public $password;
  public $password_repeat;

  public function attributeLabels()
  {
    return [
      'name' => 'Nama',
      'email' => 'Email',
      'password' => 'Password',
      'password_repeat' => 'Ulangi Password'
    ];
  }

  public function rules()
  {
    return [
      [['name', 'email', 'password', 'password_repeat'], 'required'],
      ['email', 'email'],
      ['password_repeat', 'compare', 'compareAttribute' => 'password']
    ];
  }

  public function signup()
  {
    if ($this->validate()) {
      // save user data to database
      // send verification email
      return true;
    }
    return false;
  }

}