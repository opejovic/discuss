<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use RecordsActivity;

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['thread'];

    /**
     * Attributes that are not mass assignable.
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    /**
     * Get the mentioned users from the body, if there are any.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mentionedUsers()
    {
        // Find the characters (string/word/name) after the @ symbol.
        // Does not include: space , . ; : ? ! + ~
        preg_match_all('/@([^\s\.\,\;\:\`\?\!\+\~]+)/', $this->body, $matches);

        $mentionedNames = collect($matches[1]);

        return $mentionedNames->map(function ($name) {
            return User::whereName($name)->first();
        })->filter();
    }
}
