<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = \Faker\Factory::create('zh_CN');
        $data = [
            'account' => '18208801176',
            'password' => Hash::make('123456'),
            'nickname' => 'TLITMK',
            'gender' => 1,
            'avatar' => $faker->imageUrl(256,256),
            'email' => 'xxx@xxx.com',
            'signature' => '虚幻之物对应着冥冥之路。',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
        User::create($data);
        User::factory(10)->create();
    }
}
