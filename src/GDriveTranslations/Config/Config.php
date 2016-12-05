<?php

namespace GDriveTranslations\Config;

use GDriveTranslations\Utils\Assert;

class Config
{
    const ACCESS_DRIVE = 'drive';
    const ACCESS_FILE = 'file';

    public $fileId;
    /** @var  Target[] */
    public $targets;
    public $accessType;

    public function __construct()
    {
        $this->targets = [];
        $this->accessType = self::ACCESS_DRIVE;
    }

    public static function read()
    {
        if (!file_exists('/lang/translate.json')) {
            exit('There is no translate.json file in your mapped volume');
        }

        try {
            $raw = json_decode(file_get_contents('/lang/translate.json'));
        } catch (\Exception $e) {
            exit('Cannot parse translate.json - invalid format ?');
        }
        self::validate($raw);

        return self::forgeFromRaw($raw);
    }

    public static function validate(\stdClass $raw)
    {
        Assert::propertyExists($raw, 'fileId');
        Assert::isString('fileId', $raw->fileId);
        Assert::isArray($raw, 'targets');
        foreach ($raw->targets as $target) {
            Assert::isObject('target', $target);
            Target::validate($target);
        }
    }

    public static function forgeFromRaw(\stdClass $raw)
    {
        $config = new self();
        if (isset($raw->accessType) && $raw->accessType == self::ACCESS_FILE) {
            $config->accessType = $raw->accessType;
        }
        $config->fileId = $raw->fileId;
        foreach ($raw->targets as $target) {
            $config->targets[] = Target::forgeFromRaw($target);
        }

        return $config;
    }

    public static function forgeExample($fileId)
    {
        $config = new self();
        $config->fileId = $fileId;
        $config->targets = [];
        $config->accessType = self::ACCESS_FILE;

        return $config;
    }
}
