<?php

namespace App\Traits;

use App\Activity;

trait RecordsActivity
{
    /**
     *
     */
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }

        foreach (static::getRecordEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    /**
     * Events to record the activity for.
     *
     * @return array
     */
    protected static function getRecordEvents()
    {
        return ['created'];
    }

    /**
     * @param $event
     * @throws \ReflectionException
     */
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->activityType($event),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * @return mixed
     */
    protected function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * @param $event
     * @return string
     * @throws \ReflectionException
     */
    private function activityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
}
