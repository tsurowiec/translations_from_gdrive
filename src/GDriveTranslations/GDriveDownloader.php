<?php

namespace GDriveTranslations;

use GDriveTranslations\Config\Config;
use GuzzleHttp\Psr7\Response;

class GDriveDownloader
{
    public static function download(Config $config)
    {
        try {
            $service = GDrive::getService();
        } catch (\Exception $e) {
            exit('Could not connect to gdrive: '.$e->getMessage());
        }
        /* @var Response $response */
        try {
            $response = $service->files->export($config->file, 'text/csv', ['alt' => 'media']);
            $content = $response->getBody()->getContents();
        } catch (\Exception $e) {
            exit('Could not download the spreadsheet: '.$e->getMessage());
        }
        self::saveCSVToFile($content);
    }

    private static function saveCSVToFile($csvContent)
    {
        $file = fopen('data.csv', 'w');
        fwrite($file, $csvContent);
        fclose($file);
    }
}
