<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'password' => bcrypt('Admin$%'),
            'email' => 'jbarrios@prueba.com'
        ]);

        $user = User::where('name', 'Admin')->first();
        $user->assignRole('Administrador');
    }
}
