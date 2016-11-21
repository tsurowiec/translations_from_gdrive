<?php

namespace GDriveTranslations\Source;

class Metadata
{
    public $keys;
    public $langs;

    public function __construct($row)
    {
        $this->keys = [];
        $this->langs = [];
        $this->parseForMeta($row);
    }

    private function parseForMeta($row)
    {
        foreach ($row as $key => $field) {
            switch ($field) {
                case '###':
                    break;
                case '>>>':
                    $this->keys[] = $key;
                    break;
                case '':
                    break;
                default:
                    $this->langs[$key] = $field;
                    break;
            }
        }
    }
}
