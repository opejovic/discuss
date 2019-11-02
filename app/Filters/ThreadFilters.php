<?php


namespace App\Filters;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];

    /**
     * @param  $username
     * @return Builder
     */
    public function by($username): Builder
    {
        $user = User::whereName($username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}
