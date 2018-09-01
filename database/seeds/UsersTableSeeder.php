<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'name'      => 'Abin Jose',
                'username'  => 'abin12914',
                'email'     => 'abin111manjapra@gmail.com',
                'role'      => 0,
                'password'  => bcrypt('12345678'),
            ],
            [
                'name'      => 'Mr. Admin',
                'username'  => 'admin',
                'email'     => 'admin@cpmums.com',
                'role'      => 1,
                'password'  => bcrypt('123456'),
            ],
            [
                'name'      => 'Mr. User',
                'username'  => 'user',
                'email'     => 'user@cpmums.com',
                'role'      => 2,
                'password'  => bcrypt('123456'),
            ],
        ]);
    }
}
