<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('admins')->insert([
            'username' => 'admin',
            'password' => password_hash('123456', PASSWORD_DEFAULT, ['cost' => 12]),
            'email' => '123456@qq.com',
            'mobile' => '15011111111',
            'status' => 1,
            'remember_token' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
