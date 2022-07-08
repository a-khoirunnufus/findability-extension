<?php

namespace quicknav\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use Google\Client;
use Google\Service\Drive;

class User extends ActiveRecord implements IdentityInterface
{
  public static function tableName()
  {
    return 'user';
  }

  /**
   * Finds an identity by the given token.
   *
   * @param string $token the token to be looked for
   * @return IdentityInterface|null the identity object that matches the given token.
   */
  public static function findIdentityByAccessToken($token, $type = null)
  {
    $client_secret = Yii::getAlias('@app/client_secret.json');    
    $client = new Client();
    $client->setAuthConfig($client_secret);

    // Verify Token
    try{
      $payload = $client->verifyIdToken($token);
      if (! boolval($payload)) {
        throw new \Exception();
      }
    } catch (\Exception $e) {
      return null;
    }
    
    // verification success
    $identity = static::findOne(['email' => $payload['email']]);
    // var_dump($identity); exit;
    return $identity;
  }

  /**
   * @return int|string current user ID
   */
  public function getId()
  {
    return $this->id;
  }

  public static function findIdentity($id)
  {
  }

  public function getAuthKey()
  {
  }

  public function validateAuthKey($authKey)
  {
  }
}