<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords implements Inspection
{
    protected $keywords = [
        'have win apple iphone'
    ];

    /**
     * Detect the spam in the text.
     *
     * @param  string $text
     * @throws \Exception
     * @return void
     */
    public function inspect($text)
    {
        foreach ($this->keywords as $keyword) {
            if ($this->textContainsInvalidKeyword($text, $keyword)) {
                throw new Exception;
            }
        }
    }

    /**
     * Does the text contain invalid keywords?
     *
     * @param  string $text
     * @param  string $spam
     * @return bool
     */
    private function textContainsInvalidKeyword($text, $spam)
    {
        return stripos($text, $spam) !== false;
    }
}
