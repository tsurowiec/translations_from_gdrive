<?php

namespace GDriveTranslations;

class TranslationGenerator
{
    const OUTPUT_COLUMN = 0;

    protected $metadata;

    /**
     * @param Metadata $metadata
     */
    public function setMetadata(Metadata $metadata)
    {
        $this->metadata = $metadata;
    }

    protected function isLeaf($full, $i)
    {
        return $this->level($full[$i]) >= $this->level($full[$i + 1]);
    }

    protected function level($line)
    {
        return count(array_filter(array_slice($line, $this->metadata->keys[0], count($this->metadata->keys))));
    }
}
