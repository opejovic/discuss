<?php


namespace App\Filters;


use App\User;
use Illuminate\Database\Eloquent\Builder;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'my', 'popular', 'unanswered'];

    /**
     * @param  $username
     * @return Builder
     */
    protected function by($username): Builder
    {
        $user = User::whereName($username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * @return Builder
     */
    protected function my(): Builder
    {
        return $this->builder->where('user_id', auth()->id());
    }

    /**
     * @return Builder
     */
    protected function popular(): Builder
    {
        // Clear the builder from existing orders
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * @return Builder
     */
    protected function unanswered(): Builder
    {
        return $this->builder->whereDoesntHave('replies');
    }
}
