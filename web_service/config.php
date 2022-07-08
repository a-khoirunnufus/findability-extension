<?php
return [
  'id' => 'quicknav-api',
  // the basePath of the application will be the `quicknav-app` directory
  'basePath' => __DIR__,
  // this is where the application will find all controllers
  'controllerNamespace' => 'quicknav\controllers',
  // set an alias to enable autoloading of classes from the 'quicknav' namespace
  'aliases' => [
    '@quicknav' => __DIR__,
  ],
  'components' => [
    'urlManager' => [
      'class' => 'yii\web\UrlManager',
      'showScriptName' => false,
      'enablePrettyUrl' => true,
      'rules' => [
      ],
    ],
    'db' => [
      'class' => 'yii\db\Connection',
      'dsn' => 'mysql:host=127.0.0.1;dbname=quicknav',
      'username' => 'root',
      'password' => 'password',
      'charset' => 'utf8',
    ],
    'user' => [
      'identityClass' => 'quicknav\models\User',
      'enableSession' => false,
      'loginUrl' => null,
    ],
    'request' => [
      'enableCsrfValidation' => false,
      'enableCookieValidation' => false,
      // 'cookieValidationKey' => 'stB6ZhN9oeZXURkEVhTugfQKcHIRNVfq',
    ],
  ]
];