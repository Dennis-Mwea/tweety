<?php

namespace App\Http\Controllers;

use App\Tweet;

class SearchController extends Controller
{
    public function show()
    {
        if (request()->expectsJson()) {
            return Tweet::search(request('q'))->paginate(25);
        }

        return view('search.show');
    }
}
