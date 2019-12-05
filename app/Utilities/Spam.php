<?php

namespace App\Utilities;

class Spam
{
    /**
     * Detect if the text contains spam.
     * 
     * @param  string $body
     *
     * @return bool
     * @throws \Throwable
     */
    public function detect($body)
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    /**
     * Keywords that are considered as spam.
     */
    public function detectInvalidKeywords($text)
    {
        $invalidKeywords = [
            'google customer support', 'have win apple iphone',
        ];

        foreach ($invalidKeywords as $spam) {
            if (stripos($text, $spam) !== false) {
                throw new \Exception('Spam detected!');
            }
        }
    }
}
