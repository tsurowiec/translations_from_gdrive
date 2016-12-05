<?php

namespace GDriveTranslations\Config;

use GDriveTranslations\Utils\Assert;

class Config
{
    public $file;
    /** @var  Target[] */
    public $targets;

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
        $config->file = $raw->fileId;
        foreach ($raw->targets as $target) {
            $config->targets[] = Target::forgeFromRaw($target);
        }

        return $config;
    }
}
