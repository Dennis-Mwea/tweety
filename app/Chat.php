<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static create()
 */
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

    public function senderMessages(User $sender)
    {
        return $this->messages()
            ->whereNull('read_at')
            ->where('user_id', $sender->id);
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class, 'chat_messages')
            ->latest()
            ->limit(10)
            ->withTimestamps();
    }
}
