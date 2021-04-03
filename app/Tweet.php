<?php

namespace App;

use App\Traits\Likable;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use Likable;

    protected $fillable = [
        'body', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
