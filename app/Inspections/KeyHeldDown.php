<?php

namespace App\Inspections;

use Exception;

class KeyHeldDown implements Inspection
{
    /**
     * Detect the spam in the text.
     *
     * @param  string $text
     * @return mixed
     * @throws \Throwable
     */
    public function detect($text)
    {
        if (preg_match('/(.)\\1{4,}/', $text)) {
            throw(new Exception('Spam detected!'));
        }
    }
}
