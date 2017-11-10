<?php

namespace App\Http\Controllers;

use App\Models\Reply;

class FavoritesController extends Controller
{
    /**
     * FavoritesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * @param Reply $reply
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Reply $reply)
    {
        $favorite = $reply->favorite(auth()->id());

        if (request()->wantsJson()) {
            return $favorite;
        }

        return back();
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavorite(auth()->id());
    }
}
