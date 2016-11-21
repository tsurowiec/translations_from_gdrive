<?php
/**
 * Created by PhpStorm.
 * User: suro
 * Date: 18/11/2016
 * Time: 14:42.
 */

namespace GDriveTranslations\Translator\Generator;

use GDriveTranslations\Config\Target;
use GDriveTranslations\Source\TranslationData;

interface GeneratorInterface
{
    public function generate(TranslationData $data, Target $target);
    public function supports($format);
}
