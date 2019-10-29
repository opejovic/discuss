<?php

namespace App;

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class, 'thread_id');
    }
}
