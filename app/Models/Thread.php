<?php

namespace App\Models;

use App\Filters\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = ['title', 'body'];

    protected $with = ['creator', 'channel'];

    protected $withCount = ['replies'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @param array $reply
     * @param User $user
     */
    public function addReply(array $reply, User $user)
    {
        $reply = new Reply($reply);
        $reply->owner()->associate($user);
        $reply->thread()->associate($this);
        $reply->save();
    }

    /**
     * @param Builder $query
     * @param Filters $filters
     * @return Builder
     */
    public function scopeFilter($query, Filters $filters)
    {
        return $filters->apply($query);
    }

    /**
     * @param Builder $query
     * @param $channel
     * @return Builder
     */
    public function scopeFindByChannel($query, $channel)
    {
        return $query->where('channel_id', $channel->id);
    }
}
