<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Administrator
        $admin = User::create([
        	'name' => 'Administrator',
        	'email' => 'admin@domain.com',
        	'password' => bcrypt('password'),
        	'type' => 'a',
        ]);

        $user = User::create([
        	'name' => 'Normal User',
        	'email' => 'user@domain.com',
        	'password' => bcrypt('password'),
        	'type' => 'u',
        ]);
    }
}
