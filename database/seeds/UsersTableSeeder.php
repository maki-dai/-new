<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        'over_name' => '姓',
        'under_name' =>'名',
        'over_name_kana' =>'セイ',
        'under_name_kana'=>'メイ',
        'mail_address'=>'seimei@gmail.com',
        'sex'=>'1',
        'birth_day'=>'1990-01-01',
        'role'=>'4',
        'password'=> bcrypt('seimei01'),
        ]);

    }
}
