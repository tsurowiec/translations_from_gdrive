<?php

namespace GDriveTranslations\Translator;

use GDriveTranslations\Config\Config;
use GDriveTranslations\Config\Target;
use GDriveTranslations\Source\TranslationData;
use GDriveTranslations\Translator\Generator\GeneratorInterface;

class Translator
{
    /** @var  GeneratorInterface[] */
    private $generators;

    public function __construct()
    {
        $this->generators = [];
    }

    public function addGenerator(GeneratorInterface $generator)
    {
        $this->generators[] = $generator;

        return $this;
    }

    public function generate(TranslationData $data, Config $config)
    {
        foreach ($config->targets as $target) {
            $this->generateTarget($data, $target);
        }
    }

    private function generateTarget(TranslationData $data, Target $target)
    {
        foreach ($this->generators as $generator) {
            if ($generator->supports($target->format)) {
                $generator->generate($data, $target);
                break;
            }
        }
    }
}
