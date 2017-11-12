<?php

namespace App\Models;

use App\Filters\Filters;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $fillable = ['title', 'body'];

    protected $with = ['creator', 'channel'];

    protected $withCount = ['replies'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

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
     * @param array $replyData
     * @param User $user
     * @return Reply
     */
    public function addReply(array $replyData, User $user)
    {
        $reply = new Reply($replyData);
        $reply->owner()->associate($user);
        $reply->thread()->associate($this);
        $reply->save();

        return $reply;
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
