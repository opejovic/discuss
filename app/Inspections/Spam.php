<?php

namespace App\Inspections;

use Exception;

class Spam implements Inspection
{
    /**
     * The inspection classes.
     *
     * @var array
     */
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class,
        RepliedRecently::class
    ];

    /**
     * Inspect the text for invalid words or spam.
     *
     * @param  string $body
     * @throws \Throwable
     * @return bool
     */
    public function inspect($body)
    {
        try {
            foreach ($this->inspections as $inspection) {
                app($inspection)->inspect($body);
            }

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
