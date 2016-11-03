<?php

namespace GDriveTranslations;

class Converter
{
    private $xmler;
    private $jsoner;

    public function __construct(XmlGenerator $xmler, JsonGenerator $jsoner)
    {
        $this->jsoner = $jsoner;
        $this->xmler = $xmler;
    }

    public function convert($csv)
    {
        $file = fopen('data.csv', 'w');
        fwrite($file, $csv);
        fclose($file);

        $file = fopen('data.csv', 'r');

        do {
            $line = fgetcsv($file);
        } while ($line[0] != '###');

        $metadata = new Metadata($line);

        $lines = [];
        while ($line = fgetcsv($file)) {
            $lines[] = $line;
        }
        fclose($file);

        $full = $this->transcribe($metadata, $lines);

        $this->jsoner->setMetadata($metadata);
        $this->jsoner->generate($full);

        $this->xmler->setMetadata($metadata);
        $this->xmler->generate($full);
    }

    private function transcribe(Metadata $metadata, $lines)
    {
        $full = [];
        $newline = array_fill($metadata->keys[0], count($metadata->keys), '');
        foreach ($lines as $line) {
            $newline[0] = $line[0];
            foreach ($metadata->keys as $key) {
                if ($line[$key]) {
                    $newline[$key] = $line[$key];
                    for ($i = $key + 1; $i < count($metadata->keys); ++$i) {
                        $newline[$i] = '';
                    }
                }
            }
            foreach ($metadata->langs as $key => $value) {
                $newline[$key] = $line[$key];
            }

            ksort($newline);
            $full[] = $newline;
        }

        return $full;
    }
}
