<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Route;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class, 'user_id');
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function publishThread($attributes)
    {
        return $this->threads()->create($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class, 'user_id');
    }

    /**
     * @return string
     */
    public function getMemberSinceAttribute()
    {
        return Carbon::parse($this->created_at)->format('M Y');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    /**
     * @param  $model
     * @return mixed
     */
    public function isSubscribedTo($model)
    {
        return $model->subscriptions()->where('user_id', $this->id)->exists();
    }

    /**
     * @param $thread
     *
     * @throws \Exception
     */
    public function read($thread)
    {
        $key = $this->visitedThreadCacheKey($thread);
        cache()->forever($key, now());
    }

    /**
     * @param $thread
     *
     * @return string
     */
    public function visitedThreadCacheKey($thread)
    {
        return sprintf('users.%s.visits.%s', $this->id, $thread->id);
    }

    /**
     * Has the user replied recently.
     *
     * @return bool
     */
    public function hasRepliedRecently()
    {
        return $this->replies()->where('created_at', '>=', now()->subMinute())->exists();
    }
}
