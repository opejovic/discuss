<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * Get a string representation of threads path.
     *
     * @return string
     */
    public function path()
    {
        return "threads/{$this->id}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class, 'thread_id');
    }

    public function getPublishedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d M Y');
    }

    /**
     * Add a new reply.
     *
     * @param  array $attributes
     * @return Model
     */
    public function addReply($attributes)
    {
        return $this->replies()->create($attributes);
    }
}
