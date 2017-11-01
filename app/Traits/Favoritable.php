<?php

namespace App\Traits;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    /**
     * Boot favoritable records.
     */
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * @return int
     */
    public function getFavoritesCountAttribute(): int
    {
        return $this->favorites->count();
    }

    /**
     * Favorite a reply for user.
     *
     * @param $userId
     * @return Model
     */
    public function favorite($userId)
    {
        if (! $this->isFavorited($userId)) {
            return $this->favorites()->create(['user_id' => $userId]);
        }
    }

    /**
     * @param $userId
     * @return bool
     */
    public function isFavorited($userId): bool
    {
        return ! ! $this->favorites->where('user_id', $userId)->count();
    }
}