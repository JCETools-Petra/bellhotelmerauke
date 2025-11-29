<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Petra Yoshua Marturia',
                'email' => 'rismen.ssaragih@gmail.com',
                'phone' => '628129989337',
                'email_verified_at' => '2025-08-06 02:16:42',
                'password' => '$2y$12$aEWQNAtTjoHPjaYGYZ67LuOdgYMsXnj6Pw4Xikh8JsE7N8fbOnyii', // password
                'remember_token' => null,
                'role' => 'admin',
                'created_at' => '2025-08-15 21:08:15',
                'updated_at' => '2025-08-15 21:08:15',
            ],
            [
                'id' => 3,
                'name' => '1b2454v12',
                'email' => 'wacdwadaw@gmail.com',
                'phone' => '0812571825213',
                'email_verified_at' => null,
                'password' => '$2y$12$GdmsrsKVtyMSNw1OOPkrUudFxGbnnFmVfemvrKSOSuEnbVxMrpYWG', // password
                'remember_token' => null,
                'role' => 'affiliate',
                'created_at' => '2025-08-17 23:56:14',
                'updated_at' => '2025-08-17 23:56:14',
            ],
            [
                'id' => 4,
                'name' => 'Petra Yoshua Marturia',
                'email' => 'awhwad@gmail.com',
                'phone' => '08515778112414',
                'email_verified_at' => null,
                'password' => '$2y$12$qd2vGYOaVj6LcZwCZ49F6Od56R0CkxwDoRXjBzWyIkmnvbwRotSpO', // password
                'remember_token' => null,
                'role' => 'accounting',
                'created_at' => '2025-08-18 07:49:26',
                'updated_at' => '2025-08-18 07:49:26',
            ],
        ]);
    }
}