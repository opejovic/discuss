<?php


namespace App\Inspections;


interface Inspection
{
    /**
     * Detect the spam in the text.
     *
     * @param  $text
     * @return mixed
     */
    public function detect($text);
}
