<?php

$downloadTime = -microtime(true);

use GDriveTranslations\Config\Config;
use GDriveTranslations\GDriveDownloader;
use GDriveTranslations\Source\TranslationData;
use GDriveTranslations\Translator\Translator;
use GDriveTranslations\Translator\Generator\AndroidGenerator;
use GDriveTranslations\Translator\Generator\iOSGenerator;
use GDriveTranslations\Translator\Generator\JsonGenerator;
use GDriveTranslations\Translator\Generator\XlfGenerator;

require_once __DIR__.'/vendor/autoload.php';

//error_reporting(0);
date_default_timezone_set('Europe/Warsaw');

echo "\nTranslations from GDrive\n========================\n\n";

if (php_sapi_name() != 'cli') {
    exit('This application must be run on the command line.');
}

if (file_exists('/lang/translate.json')) {

    $config = Config::read();

    GDriveDownloader::download($config);
    $parseTime = -microtime(true);
    $downloadTime += microtime(true);

    $data = TranslationData::forgeFromContent();

    $translator = new Translator();
    $translator
        ->addGenerator(new JsonGenerator())
        ->addGenerator(new XlfGenerator())
        ->addGenerator(new iOSGenerator())
        ->addGenerator(new AndroidGenerator())
        ->generate($data, $config);

    $parseTime += microtime(true);
    echo 'took '.round($downloadTime * 1000).'ms. downloading, '.round($parseTime * 1000).'ms. parsing. Peak memory usage: '.round(memory_get_peak_usage(true) / 1024 / 1024, 2)."MB\n\n";
} else {
    $line = readline('No translate.json found. Enter spreadsheet name to initialize [empty to cancel] > ');
    echo "\n";
    if(!$line) {
        exit('Cancelled'."\n\n");
    }
    $fileId = GDriveDownloader::create($line);
    echo "\nTransactions initialized, spreadsheet has been created in GDrive, translate.json has been saved. \n";
    echo "Please edit translate.json to add targets.\n\n";
}
