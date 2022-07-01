<?php

namespace fex\components;

use GuzzleHttp\Client;

class DriveApi
{
  public static function file_list()
  {
    $client = new Client([
      // Base URI is used with relative requests
      'base_uri' => 'http://httpbin.org',
      // You can set any number of default request options.
      'timeout'  => 2.0,
    ]);
    $response = $client->request('GET', '/get');
    return $response;
  }
}
