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
            $level = $data->level($line);
            if ($level < $lvl) {
                break;
            }
            if ($level == $lvl) {
                if ($i == count($data->rows) || $data->isLeaf($i)) {
                    if ($data->isExported($line, $target->sections, $target->tags)) {
                        $res[$line[$data->metadata->keys[$lvl - 1]]] = $line[$value];
                    }
                } else {
                    $res[$line[$data->metadata->keys[$lvl - 1]]] = $this->walk($data, $target, $lvl + 1, $i + 1, $value);
                }
            }
        }

        return $res;
    }
}
