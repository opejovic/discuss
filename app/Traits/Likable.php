<?php


namespace App\Traits;

use App\Like;
use Illuminate\Database\Eloquent\Model;

trait Likable
{
    /**
     * Model can be liked.
     *
     * @return Model
     */
    public function like()
    {
        return $this->likes()->create(['user_id' => auth()->id()]);
    }

    /**
     * Model can be unliked.
     *
     * @return Model
     * @throws \Exception
     */
    public function unlike()
    {
        return $this->likes()->where('user_id', auth()->id())->first()->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    /**
     * @return bool
     */
    public function getHasBeenLikedAttribute()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
}
