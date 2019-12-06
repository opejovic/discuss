<?php

namespace App\Inspections;

class Spam implements Inspection
{
    /**
     * The inspection classes.
     *
     * @var array
     */
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

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
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }
}
