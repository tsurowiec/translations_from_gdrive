<?php

use GuzzleHttp\Psr7\Response;

require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('Europe/Warsaw');

if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}

$config = json_decode(file_get_contents('/lang/translate.json'));

$service = GDrive::getService();

// Print the names and IDs for up to 10 files.
$optParams = array(
  'pageSize' => 10,
  'fields' => 'nextPageToken, files(id, name)'
);

/** @var Response $response */
$response = $service->files->export($config->fileId, 'text/csv', ['alt' => 'media']);
$content = $response->getBody()->getContents();
ConvertCsv2Json::convert($content);