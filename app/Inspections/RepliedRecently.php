<?php

namespace App\Inspections;

use Exception;
use Illuminate\Support\Facades\Route;

class RepliedRecently implements Inspection
{
    // temporary
    protected $routes = ['replies.store', 'threads.store'];

    /**
     * Detect the spam in the text.
     *
     * @param  string $text
     * @return mixed
     * @throws \Throwable
     */
    public function inspect($text)
    {
        if (auth()->user()->hasRepliedRecently() && $this->tryingToPublish()) {
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
    public function tryingToPublish()
    {
        return collect($this->routes)->contains(Route::currentRouteName());
    }
}
