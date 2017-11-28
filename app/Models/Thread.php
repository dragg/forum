<?php

namespace App\Models;

use App\Filters\Filters;
use App\Notifications\ThreadWasUpdated;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $fillable = ['title', 'body'];

    protected $with = ['creator', 'channel'];

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

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
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

    /**
     * @return bool
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->isSubscribedTo(auth()->user());
    }

    public function subscribe($userId)
    {
        if ($userId instanceof User) {
            $userId = $userId->id;
        }

        $this->subscriptions()->create([
            'user_id' => $userId,
        ]);

        return $this;
    }

    public function unsubscribe($userId)
    {
        if ($userId instanceof User) {
            $userId = $userId->id;
        }

        $this->subscriptions()
            ->where('user_id', $userId)
            ->delete();

        return $this;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function isSubscribedTo($userId): bool
    {
        if ($userId instanceof User) {
            $userId = $userId->id;
        }

        return $this->subscriptions()->where('user_id', $userId)->exists();
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

        // Prepare notifications for all subscribers.
        $this->subscriptions
            ->filter(function ($subscription) use ($user) {
                return $subscription->user_id != $user->id;
            })
            ->each(function ($subscription) use ($reply) {
                $subscription->notify(new ThreadWasUpdated($this, $reply));
            });

        return $reply;
    }
}
