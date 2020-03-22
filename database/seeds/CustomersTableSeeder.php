<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert(['name' => 'Suramericana', 'document' => '78459856', 'email' => 'sura@prueba.com', 'address' => 'Calle 45 #65 - 12']);
        DB::table('customers')->insert(['name' => 'Bancolombia', 'document' => '98568547', 'email' => 'bancolombia@prueba.com', 'address' => 'Calle 70 #80 - 12']);
        DB::table('customers')->insert(['name' => 'Protección', 'document' => '54788569', 'email' => 'proteccion@prueba.com', 'address' => 'Cra 45 #65 - 12']);
        DB::table('customers')->insert(['name' => 'EPM', 'document' => '51246325', 'email' => 'epm@prueba.com', 'address' => 'Cra 70 #80 - 12']);
        DB::table('customers')->insert(['name' => 'Coomeva', 'document' => '67913456', 'email' => 'coomeva@prueba.com', 'address' => 'Calle 57 #65 - 61']);
        DB::table('customers')->insert(['name' => 'Avianca', 'document' => '48793258', 'email' => 'avianca@prueba.com', 'address' => 'Calle 10 #12 - 21']);
    }
}
