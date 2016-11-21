<?php

namespace GDriveTranslations\Config;

use GDriveTranslations\Utils\Assert;

class Target
{
    public $format;
    public $sections;
    public $pattern;

    public static function forgeFromRaw(\stdClass $raw)
    {
        $target = new self();
        $target->format = $raw->format;
        $target->pattern = property_exists($raw, 'pattern') ? $raw->pattern : '_default';
        $target->sections = property_exists($raw, 'sections') ? $raw->sections : ['_all'];

        return $target;
    }

    public static function validate(\stdClass $raw)
    {
        Assert::propertyExists($raw, 'format');
        if (property_exists($raw, 'sections')) {
            Assert::isArray($raw, 'sections');
            foreach ($raw->sections as $section) {
                Assert::isString('section name', $section);
            }
        }
    }

    public function checkPattern($default)
    {
        if ($this->pattern !== '_default') {
            return;
        }
        $this->pattern = $default;
    }
}
