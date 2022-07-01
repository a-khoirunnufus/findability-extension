<?php

return [
  'id' => 'findability-extension-api',
  // the basePath of the application will be the `findability-extension-api` directory
  'basePath' => __DIR__,
  // this is where the application will find all controllers
  'controllerNamespace' => 'fex\controllers',
  // set an alias to enable autoloading of classes from the 'fex' namespace
  'aliases' => [
    '@fex' => __DIR__,
  ],
  'components' => [
    'request' => [
        'enableCookieValidation' => false,
        'enableCsrfValidation' => false
    ],
    'urlManager' => [
      'enablePrettyUrl' => true,
      'enableStrictParsing' => true,
      'showScriptName' => false,
      'rules' => [
        'GET view/files/<parent_id>' => 'file/view-files',
        'GET auth/signin' => 'auth/signin',
        'POST auth/signincallback' => 'auth/signincallback',
        'GET auth/signout' => 'auth/signout',
        'GET auth/oauth2' => 'auth/oauth2',
        'GET auth/oauth2callback' => 'auth/oauth2callback',
        'GET auth/post-signin' => 'auth/post-signin'
      ]
    ]
  ],
];