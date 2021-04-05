<?php

namespace App;

use App\Http\Resources\MessageCollection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $id)
 */
class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => Carbon::now()])->save();
        }
    }

    public function markAsUnRead()
    {
        if (!is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }

    public function newCollection(array $models = [])
    {
        return new MessageCollection($models);
    }
}
