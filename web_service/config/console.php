<?php

$config = [
  'id' => 'quicknav-console',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'controllerNamespace' => 'quicknav\commands',
  'aliases' => [
    '@bower' => '@vendor/bower-asset',
    '@npm'   => '@vendor/npm-asset',
    '@tests' => '@app/tests',
  ],
  'components' => [
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'log' => [
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
    'db' => [
      'class' => 'yii\db\Connection',
      'dsn' => 'mysql:host=127.0.0.1;dbname=quicknav',
      'username' => 'root',
      'password' => 'password',
      'charset' => 'utf8',
    ],
  ],
];

return $config;
