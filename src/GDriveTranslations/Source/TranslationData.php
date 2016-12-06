<?php

namespace GDriveTranslations\Source;

class TranslationData
{
    /** @var  Metadata */
    public $metadata;
    public $rows;

    public static function forgeFromContent()
    {
        $data = new self();
        $data->rows = $data->completeParentLevels($data->load());

        return $data;
    }

    private function load()
    {
        $file = fopen('data.csv', 'r');

        do {
            $line = fgetcsv($file);
            if (feof($file)) {
                exit("No data to parse... exiting\n");
            }
        } while (!in_array('>>>', $line));

        $this->metadata = new Metadata($line);

        $lines = [];
        while ($line = fgetcsv($file)) {
            $lines[] = $line;
        }
        fclose($file);

        return $lines;
    }

    private function completeParentLevels($lines)
    {
        $rows = [];
        $newline = array_fill($this->metadata->keys[0], count($this->metadata->keys), '');
        foreach ($lines as $line) {
            foreach ($this->metadata->keys as $key) {
                if ($line[$key]) {
                    $newline[$key] = $line[$key];
                    for ($i = $key + 1; $i <= count($this->metadata->keys); ++$i) {
                        $newline[$i] = '';
                    }
                }
            }
            foreach ($this->metadata->langs as $key => $value) {
                $newline[$key] = $line[$key];
            }
            $newline[$this->metadata->tags] = $line[$this->metadata->tags];
            ksort($newline);
            $rows[] = $newline;
        }

        return $rows;
    }

    public function isLeaf($i)
    {
        return $this->level($this->rows[$i]) >= $this->level($this->rows[$i + 1]);
    }

    public function level($row)
    {
        $keys = [];
        foreach ($this->metadata->keys as $key) {
            $keys[] = $row[$key];
        }

        return count(array_filter($keys));
    }

    public function isExported($line, $sections, $requestedTags)
    {
        return $this->inExportedSection($line, $sections) && $this->inExportedTag($line, $requestedTags);
    }

    private function inExportedSection($line, $sections)
    {
        return in_array('_all', $sections) || in_array($line[$this->metadata->keys[0]], $sections);
    }

    private function inExportedTag($line, $requestedTags)
    {
        $exportedTag = true;
        if (count($requestedTags)) {
            $tags = array_filter(array_map('trim', explode(',', $line[$this->metadata->tags])));
            if (count($tags)) {
                $exportedTag = count(array_intersect($requestedTags, $tags)) > 0;
            }
        }

        return $exportedTag;
    }
}
