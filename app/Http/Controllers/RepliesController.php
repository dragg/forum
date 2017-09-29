<?php

namespace App\Http\Controllers;

use App\Models\Thread;

class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Thread $thread)
    {
        $thread->addReply(['body' => request('body')], auth()->user());

        return redirect()->route('threads.show', [$thread->channel->slug, $thread]);
    }
}
