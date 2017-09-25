<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
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
}
