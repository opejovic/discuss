<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * Attributes that are not mass assignable.
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * @param  User $user
     * @param       $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function feed(User $user, $take = 50): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->get()
            ->take($take)
            ->groupBy(function ($activity) {
                return $activity->created_at->format('d M Y');
            });
    }
}
