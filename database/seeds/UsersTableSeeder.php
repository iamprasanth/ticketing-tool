<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  		$users = [
            'admin@test.com' => [
                'email' => 'admin@test.com',
                'password' => 'admin@123#',
                'first_name' => 'Admin',
                'middle_name' => ' ',
                'last_name' => 'Admin',
				'role' => 1,
            ]
        ];
		foreach ($users as $email => $user) {
            $userexist = DB::table('users')
                ->where('email', '=', $email)
                ->first();
            if (is_null($userexist)) {
                $userid = DB::table('users')->insertGetId([
                    'email' => $user['email'],
                    'password' => bcrypt($user['password']),
					'role_id' => $user['role'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                DB::table('user_info')->insert([
                    'user_id' => $userid,
                    'first_name' => $user['first_name'],
					'middle_name' => $user['middle_name'],
					'last_name' => $user['last_name'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }
}
