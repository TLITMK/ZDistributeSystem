<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \DB::table('users')->insert([
            'nickname'=>'TLITMK',
            'email'=>'tlitmk@zz.com',
            'password'=>Hash::make('123456')
        ]);
    }
}
