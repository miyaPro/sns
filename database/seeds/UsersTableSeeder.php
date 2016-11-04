<?php

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
        DB::table('users')->truncate();
        DB::table('users')->insert(
            [
                [
                    'name'              => 'client',
                    'email'             => 'client@wevnal.co.jp',
                    'password'          => bcrypt('123456'),
                    'contract_status'   => '001',
                    'company_name'      => 'Miyatsu',
                    'url'               => 'http://miyatsu.vn',
                    'authority'         => config('constants.authority.client'),
                ],
                [
                    'name'              => 'admin',
                    'email'             => 'admin@wevnal.co.jp',
                    'password'          => bcrypt('123456'),
                    'contract_status'   => '001',
                    'company_name'      => 'Miyatsu',
                    'url'               => 'http://miyatsu.vn',
                    'authority'         => config('constants.authority.admin'),
                ]
            ]

        );
    }
}
