<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chat extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    protected $with = ['messages', 'participants'];

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string)Str::uuid();
            }
        });
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'chat_participants')
            ->withTimestamps();
    }

    public function userMessages(User $user)
    {
        return $this->messages()
            ->whereNull('read_at')
            ->where('user_id', $user->id)
            ->get();
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class, 'chat_messages')
            ->latest()
            ->take(10)
            ->withTimestamps();
    }
}
