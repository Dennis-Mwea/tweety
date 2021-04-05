<?php

namespace App\Http\Controllers\Api;

class TweetsController extends BaseApiController
{
    public function index()
    {
        return $this->sendResponse(current_user()->timeline()->jsonPaginate());
    }
}
