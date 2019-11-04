<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Thread extends Model
{
    protected $guarded = [];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replies_count', function (Builder $builder) {
            $builder->withCount('replies');
        });
    }

    /**
     * Get a string representation of threads path.
     *
     * @return string
     */
    public function path()
    {
        return "threads/{$this->channel->slug}/{$this->id}";
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

    /**
     * @return string
     */
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    /**
     * @param  $filters
     * @param  $query
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Thread can be liked.
     *
     * @return Model
     */
    public function like()
    {
        return $this->likes()->create(['user_id' => auth()->id()]);
    }

    /**
     * Thread can be unliked.
     *
     * @return Model
     */
    public function unlike()
    {
        return $this->likes()->where('user_id', auth()->id())->first()->delete();
    }

    /**
     * @return bool
     */
    public function getHasBeenLikedAttribute()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
}
