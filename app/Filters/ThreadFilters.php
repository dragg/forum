<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
    /**
     * @var array
     */
    protected $filters = ['by', 'popular'];

    /**
     * Filter the query by a given username.
     *
     * @param $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query according to most popular threads.
     *
     * @return Builder
     */
    public function popular()
    {
        $this->builder->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }
}
