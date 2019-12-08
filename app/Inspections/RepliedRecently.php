<?php

namespace App\Inspections;

use Exception;
use Illuminate\Support\Facades\Route;

class RepliedRecently implements Inspection
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
        if (auth()->user()->hasRepliedRecently() && $this->routeIsStore()) {
            $this->warn();
        }
    }

    /**
     * @throws \Exception
     */
    public function warn()
    {
        throw new Exception('You are posting too frequently, chill for a minute.');
    }

    /**
     * @return bool
     */
    public function routeIsStore()
    {
        return Route::currentRouteName() == 'replies.store';
    }
}
