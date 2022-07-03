<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

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
      ['email', 'validateEmailNotExists'],
      ['password_repeat', 'compare', 'compareAttribute' => 'password']
    ];
  }

  public function validateEmailNotExists($attribute, $params, $validator, $current)
  {
    if (User::findOne(['email' => $current]) !== null) {
      $this->addError($attribute, 'Email ini sudah terdaftar pada sistem.');
    }
  }

  public function signup()
  {
    if ($this->validate()) {
      // save user data to database
      $hash_password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
      $verification_code = Yii::$app->getSecurity()->generateRandomString();

      $user = new User();
      $user->name = $this->name;
      $user->email = $this->email;
      $user->is_email_verified = true;
      // $user->email_verification_code = $verification_code;
      $user->password = $hash_password;

      // var_dump($user); exit;
      $user->save();

      // send verification email
      return true;
    }
    return false;
  }

}