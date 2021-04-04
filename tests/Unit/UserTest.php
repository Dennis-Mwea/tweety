<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testAUserCanDetermineTheirAvatarPath()
    {
        $user = create(User::class);

        $this->assertEquals(asset('images/default-avatar.png'), $user->avatar);

        $user->avatar = 'avatars/me.jpg';

        $this->assertEquals(asset('avatars/me.jpg'), $user->avatar);
    }

    public function testAUserCanDetermineTheirBannerPath()
    {
        $user = factory(\App\User::class)->create();

        $this->assertEquals(asset('images/default-profile-banner.jpeg'), $user->banner);

        $user->banner = 'banners/my-banner.jpg';

        $this->assertEquals(asset('banners/my-banner.jpg'), $user->banner);
    }
}
