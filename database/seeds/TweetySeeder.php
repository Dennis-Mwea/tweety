<?php

use App\Tweet;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TweetySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->users()->tweets();

        Schema::enableForeignKeyConstraints();
    }

    public function tweets()
    {
        Tweet::truncate();

        $users = User::all();

        $users->each(function ($user) {
            factory(Tweet::class, 3)->create(['user_id' => $user->id]);
        });
    }

    public function users()
    {
        User::truncate();

        collect([
            [
                'name' => 'John Doe',
                'username' => 'johndoe',
                'email' => 'john@example.com',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Darth Vader',
                'username' => 'vader',
                'email' => 'vader@example.com',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Luke Skywalker',
                'username' => 'luke',
                'email' => 'luke@example.com',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Indiana Jones',
                'username' => 'rotla1981',
                'email' => 'indy@example.com',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Ben Solo',
                'username' => 'KyloRen',
                'email' => 'kylo@example.com',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Marty McFly',
                'username' => '121gigawatts',
                'email' => 'calvin@example.com',
                'password' => bcrypt('password')
            ],
        ])->each(function ($user) {
            factory(User::class)->create(
                [
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'password' => bcrypt('password')
                ]
            );
        });

        return $this;
    }
}
