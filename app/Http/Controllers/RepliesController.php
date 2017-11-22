<?php

namespace App\Http\Controllers;

use App\Http\Requests\Threads\ReplyStore;
use App\Models\Reply;
use App\Models\Thread;

class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }


    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate();
    }

    /**
     * @param ReplyStore $request
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReplyStore $request, Thread $thread)
    {
        $reply = $thread->addReply(['body' => $request->get('body')], auth()->user());

        if ($request->wantsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->wantsJson()) {
            return response([]);
        }

        return back();
    }
}
