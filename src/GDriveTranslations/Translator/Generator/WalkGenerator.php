<?php

namespace GDriveTranslations\Translator\Generator;

use GDriveTranslations\Config\Target;
use GDriveTranslations\Source\TranslationData;

abstract class WalkGenerator extends BaseGenerator
{
    protected function walk(TranslationData $data, Target $target, $lvl, $start, $value)
    {
        $res = [];
        for ($i = $start; $i < count($data->rows); ++$i) {
            $line = $data->rows[$i];
            if ($data->isExported($line, $target->sections)) {
                $level = $data->level($line);
                if ($level < $lvl) {
                    break;
                }
                if ($level == $lvl) {
                    $res[$line[$data->metadata->keys[$lvl - 1]]] =
                        $i == count($data->rows) - 1 || $data->isLeaf($i)
                            ? $line[$value] : $this->walk($data, $target, $lvl + 1, $i + 1, $value);
                }
            }
        }

        return $res;
    }
}
