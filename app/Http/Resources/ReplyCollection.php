<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;

class ReplyCollection extends Collection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function threaded()
    {
        $replies = parent::groupBy('parent_id');

        if (count($replies)) {
            $replies['root'] = $replies[''];
            unset($replies['']);
        }

        return $replies;
    }
}
