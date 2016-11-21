<?php

namespace GDriveTranslations\Translator\Generator;

abstract class BaseGenerator
{
    const DIRECTORY = '/lang';

    protected function save($content, $lang, $pattern)
    {
        $path = self::DIRECTORY;
        $parts = explode('/', $pattern);
        $file = array_pop($parts);
        foreach ($parts as $dir) {
            $path .= '/'.$dir;
            $path = str_replace('%locale%', $lang, $path);
            mkdir($path);
        }
        $filename = str_replace('%locale%', $lang, $path.'/'.$file);
        $file = fopen($filename, 'w');
        fwrite($file, $content);
        fclose($file);
    }
}
