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
    public function inspect($text)
    {
        if ($this->textContainsKeyHeldDown($text)) {
            $this->warn();
        }
    }

    /**
     * Does the text contain key held down characters?
     *
     * @param  $text
     * @return false|int
     */
    private function textContainsKeyHeldDown($text)
    {
        return preg_match('/(.)\\1{4,}/', $text);
    }

    /**
     * @throws \Exception
     */
    private function warn()
    {
        throw new Exception('Holding down keys much? Cat sat on your keyboard?');
    }
}
