<?php

namespace App;

use App\Traits\Followable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

/**
 * @method static where(string $string, mixed $email)
 * @method static create(array $array)
 */
class User extends Authenticatable
{
    use Notifiable;
    use Followable;
    use Searchable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'avatar', 'email', 'password', 'banner', 'description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'avatar', 'follows_count', 'followers_count', 'is_followed'
    ];

    public function tweets()
    {
        return $this->hasMany(Tweet::class)->latest();
    }

    public function getAvatarAttribute($value)
    {
        return asset($value ?: '/images/default-avatar.png');
    }

    public function timeline()
    {
        $friends = $this->follows()->pluck('id');

        return Tweet::whereIn('user_id', $friends)
            ->orWhere('user_id', $this->id)
            ->latest();
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function getBannerAttribute($value)
    {
        return asset($value ?: '/images/default-profile-banner.jpeg');
    }

    public function toSearchableArray()
    {
        return $this->toArray() + ['path' => $this->path()];
    }

    public function path($append = '')
    {
        $path = route('profile', $this->username);

        return $append ? "{$path}/{$append}" : "$path";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)
            ->with('tweet', 'parent')
            ->latest();
    }
}
