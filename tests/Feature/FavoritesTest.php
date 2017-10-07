<?php

namespace Tests\Feature;

use App\Models\Reply;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    /** @test */
    public function guests_can_not_favorite_anything()
    {
        $this->post(route('replies.favorite', [1]))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->postJson(route('replies.favorite', [$reply]))
            ->assertStatus(200);

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->postJson(route('replies.favorite', [$reply]))->assertStatus(200);
        $this->postJson(route('replies.favorite', [$reply]))->assertStatus(200);

        $this->assertCount(1, $reply->favorites);
    }

}
