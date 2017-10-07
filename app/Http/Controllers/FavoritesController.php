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
        return $reply->favorite(auth()->id());
    }
}
