<?php

namespace App\Http\Controllers\Api;

use App\Tweet;
use App\User;

class SearchController extends BaseApiController
{
    public function show()
    {
        return $this->sendResponse($this->mapSearchTypeToQuery(request('type'), request('q')));
    }

    public function mapSearchTypeToQuery($type, $query)
    {
        $model = [
            'tweet' => Tweet::search($query)->get(),
            'user' => User::search($query)->get()
        ];

        return $model[$type];
    }
}
