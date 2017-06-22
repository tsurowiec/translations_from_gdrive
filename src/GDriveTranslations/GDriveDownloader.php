<?php

namespace GDriveTranslations;

use GDriveTranslations\Config\Config;
use GuzzleHttp\Psr7\Response;

class GDriveDownloader
{
    /**
     * @var \Google_Service_Drive
     */
    private $drive;

    public function __construct(\Google_Service_Drive $drive)
    {
        $this->drive = $drive;
    }

    public function download(Config $config)
    {
        /* @var Response $response */
        try {
            $response = $this->drive->files->export($config->fileId, 'text/csv', ['alt' => 'media']);
            $content = $response->getBody()->getContents();
        } catch (\Exception $e) {
            exit('Could not download the spreadsheet: '.$e->getMessage());
        }

        return $content;
    }

    public function create($filename)
    {
        $copiedFile = new \Google_Service_Drive_DriveFile();
        $copiedFile->setName($filename);

        try {
            $gFile = $this->drive->files->copy('1AUAKxhuZyjYl4NdpQCLBcSZe2snKAOjcXArlHRIn_hM', $copiedFile);

            return $gFile;
        } catch (\Exception $e) {
            exit('Could not create spreadsheet: '.$e->getMessage());
        }
    }
}
