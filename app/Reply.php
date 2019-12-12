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
        // Find the words (names) (string/word/name) after the @ symbol. Include the '-',
        // as the usernames can contain '-'.
        // Example: "Hey @Jane-Doe!" will return "Jane-Doe",
        // which is the users name, and it will get that user.

        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        $mentionedNames = collect($matches[1]);

        return User::whereIn('name', $mentionedNames)->get();
    }

    /**
     * Function description
     *
     * @param
     * @return
     */
    public function setBodyAttribute($body)
    {
        // $1 - Matching name without the @ symbol.
        // $0 - Matching name with the @ symbol.
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a class="text-blue-400" href="/profiles/$1">$0</a>', $body);
    }
}
