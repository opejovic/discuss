<?php

namespace App;

use Carbon\Carbon;
use App\Traits\Likable;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Thread extends Model
{
    use Likable, RecordsActivity;

    /**
     * Attributes that are not mass assignable.
     */
    protected $guarded = [];

    /**
     * Relationships to include in every query.
     */
    protected $with = ['channel'];

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

        static::addGlobalScope('author', function (Builder $builder) {
            $builder->with('author');
        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
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
        return $this->hasMany(Reply::class, 'thread_id')->with('author');
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
     * @param  $filters
     * @param  $query
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

}
