<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

        ];
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt(123123),
                'roles' => 'ADMIN',
            ],
            [
                'name' => 'apoteker',
                'email' => 'apoteker@apoteker.com',
                'password' => bcrypt(123123),
                'roles' => 'APOTEKER',
            ]
        ]
    );
    }
}
