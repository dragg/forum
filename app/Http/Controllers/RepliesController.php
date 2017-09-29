<?php

namespace App\Http\Controllers;

use App\Http\Requests\Threads\ReplyStore;
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
     * @param ReplyStore $request
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReplyStore $request, Thread $thread)
    {
        $thread->addReply(['body' => $request->get('body')], auth()->user());

        return redirect()->route('threads.show', [$thread->channel->slug, $thread]);
    }
}
