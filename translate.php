<?php

$whole -= microtime(true);

use GDriveTranslations\Converter;
use GDriveTranslations\GDrive;
use GDriveTranslations\JsonGenerator;
use GDriveTranslations\XmlGenerator;
use GuzzleHttp\Psr7\Response;

require_once __DIR__.'/vendor/autoload.php';
date_default_timezone_set('Europe/Warsaw');

echo "\nTranslations from GDrive\n========================\n\n";

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}
if (!file_exists('/lang/translate.json')) {
    exit('There is no translate.json file in your mapped volume');
}

$config = json_decode(file_get_contents('/lang/translate.json'));

if (!$config->fileId) {
    exit('There is no file ID in translate.json file');
}

$targets = [
    'json',
    'xlf',
];

if(is_array($config->targets))
{
    $targets = $config->targets;
}

try {
    $service = GDrive::getService();
} catch (\Exception $e) {
    exit('Could not connect to gdrive: '.$e->getMessage());
}

/* @var Response $response */
try {
    $response = $service->files->export($config->fileId, 'text/csv', ['alt' => 'media']);
    $content = $response->getBody()->getContents();
} catch (\Exception $e) {
    exit('Could not download the spreadsheet: '.$e->getMessage());
}
$time = -microtime(true);
$whole += microtime(true);
$converter = new Converter(new XmlGenerator(), new JsonGenerator());
$converter->convert($content, $targets);
$time += microtime(true);
echo "\ntook : ".round($whole * 1000).'ms. downloading, '.round($time * 1000)."ms. parsing, peak memory usage: ".round(memory_get_peak_usage(true)/1024/1024,2)."MB\n\n";
