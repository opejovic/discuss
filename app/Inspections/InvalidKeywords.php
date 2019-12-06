<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords implements Inspection
{
    protected $keywords = [
        'google customer support', 'have win apple iphone',
    ];

    /**
     * Detect the spam in the text.
     *
     * @param  string $text
     * @return mixed
     * @throws \Exception
     */
    public function detect($text)
    {
        foreach ($this->keywords as $spam) {
            if (stripos($text, $spam) !== false) {
                throw new Exception('Spam detected!');
            }
        }
    }
}
