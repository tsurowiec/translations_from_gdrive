<?php

namespace GDriveTranslations;

class JsonGenerator extends TranslationGenerator
{
    public function generate(&$full)
    {
        $res = [];
        foreach ($this->metadata->langs as $key => $value) {
            $res[$value] = $this->walk($full, 1, 0, $key);
        }
        $this->save($res);
    }

    private function save($res)
    {
        foreach ($res as $key => $value) {
            $file = fopen('/lang/'.$key.'.json', 'w');
            fwrite($file, json_encode($value, JSON_PRETTY_PRINT));
            fclose($file);
        }
    }

    private function walk(&$full, $lvl, $start, $value)
    {
        $res = [];
        for ($i = $start; $i < count($full); ++$i) {
            $line = $full[$i];
            if ($this->isExported($line)) {
                $level = $this->level($line);
                if ($level < $lvl) {
                    break;
                }
                if ($level == $lvl) {
                    $res[$line[$this->metadata->keys[$lvl - 1]]] =
                        $i == count($full) - 1 || $this->isLeaf($full, $i)
                            ? $line[$value] : $this->walk($full, $lvl + 1, $i + 1, $value);
                }
            }
        }

        return $res;
    }

    private function isExported($line)
    {
        return in_array($line[self::OUTPUT_COLUMN], ['', 'y']);
    }
}
