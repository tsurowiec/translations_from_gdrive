<?php

namespace GDriveTranslations;

class Metadata
{
    public $keys;
    public $langs;

    public function __construct($row)
    {
        $this->parseForMeta($row);
    }

    private function parseForMeta($row)
    {
        $this->keys = [];
        $this->langs = [];

        foreach ($row as $key => $field) {
            switch ($field) {
                case '###':
                    break;
                case '>>>':
                    $this->keys[] = $key;
                    break;
                default:
                    $this->langs[$key] = $field;
                    break;
            }
        }
    }
}
