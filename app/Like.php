<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $guarded = [];

    protected $appends = ['is_liked_by', 'is_disliked_by'];
}
