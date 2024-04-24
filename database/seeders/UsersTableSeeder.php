<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Tanim',
                'email' => 'tanimchy417@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$pcVP5fo4gPVF.Uz0gZziued9Cz3RsiDJNkOVWYdDiJFznrbzx47uK',
                'remember_token' => NULL,
                'created_at' => '2024-04-07 08:14:56',
                'updated_at' => '2024-04-07 08:14:56',
            ),
        ));
        
        
    }
}