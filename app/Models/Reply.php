<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['body'];

    /**
     * A reply has one owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply relate to one thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * Favorite a reply for user.
     *
     * @param $userId
     * @return Model
     */
    public function favorite($userId)
    {
        if (! $this->hasFavorited($userId)) {
            return $this->favorites()->create(['user_id' => $userId]);
        }
    }

    /**
     * @param $userId
     * @return bool
     */
    protected function hasFavorited($userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
}
