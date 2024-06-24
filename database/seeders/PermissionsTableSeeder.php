<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Menu Label create',
                'guard_name' => 'web',
                'parent' => 'Menu Label',
                'created_at' => '2024-05-15 15:31:49',
                'updated_at' => '2024-05-15 15:31:49',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Menu Label view',
                'guard_name' => 'web',
                'parent' => 'Menu Label',
                'created_at' => '2024-05-15 15:31:49',
                'updated_at' => '2024-05-15 15:31:49',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Menu Label edit',
                'guard_name' => 'web',
                'parent' => 'Menu Label',
                'created_at' => '2024-05-15 15:31:49',
                'updated_at' => '2024-05-15 15:31:49',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Menu Label destroy',
                'guard_name' => 'web',
                'parent' => 'Menu Label',
                'created_at' => '2024-05-15 15:31:49',
                'updated_at' => '2024-05-15 15:31:49',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Menu Label status',
                'guard_name' => 'web',
                'parent' => 'Menu Label',
                'created_at' => '2024-05-15 15:31:49',
                'updated_at' => '2024-05-15 15:31:49',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Menu Label restore',
                'guard_name' => 'web',
                'parent' => 'Menu Label',
                'created_at' => '2024-05-15 15:31:49',
                'updated_at' => '2024-05-15 15:31:49',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Menu Label delete',
                'guard_name' => 'web',
                'parent' => 'Menu Label',
                'created_at' => '2024-05-15 15:31:49',
                'updated_at' => '2024-05-15 15:31:49',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Menu Label print',
                'guard_name' => 'web',
                'parent' => 'Menu Label',
                'created_at' => '2024-05-15 15:31:49',
                'updated_at' => '2024-05-15 15:31:49',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Menu Label trash',
                'guard_name' => 'web',
                'parent' => 'Menu Label',
                'created_at' => '2024-05-15 15:31:49',
                'updated_at' => '2024-05-15 15:31:49',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Menu create',
                'guard_name' => 'web',
                'parent' => 'Menu',
                'created_at' => '2024-05-15 15:32:30',
                'updated_at' => '2024-05-15 15:32:30',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Menu view',
                'guard_name' => 'web',
                'parent' => 'Menu',
                'created_at' => '2024-05-15 15:32:30',
                'updated_at' => '2024-05-15 15:32:30',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Menu edit',
                'guard_name' => 'web',
                'parent' => 'Menu',
                'created_at' => '2024-05-15 15:32:30',
                'updated_at' => '2024-05-15 15:32:30',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Menu destroy',
                'guard_name' => 'web',
                'parent' => 'Menu',
                'created_at' => '2024-05-15 15:32:30',
                'updated_at' => '2024-05-15 15:32:30',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Menu status',
                'guard_name' => 'web',
                'parent' => 'Menu',
                'created_at' => '2024-05-15 15:32:30',
                'updated_at' => '2024-05-15 15:32:30',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Menu restore',
                'guard_name' => 'web',
                'parent' => 'Menu',
                'created_at' => '2024-05-15 15:32:30',
                'updated_at' => '2024-05-15 15:32:30',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Menu delete',
                'guard_name' => 'web',
                'parent' => 'Menu',
                'created_at' => '2024-05-15 15:32:30',
                'updated_at' => '2024-05-15 15:32:30',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Menu print',
                'guard_name' => 'web',
                'parent' => 'Menu',
                'created_at' => '2024-05-15 15:32:30',
                'updated_at' => '2024-05-15 15:32:30',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Menu trash',
                'guard_name' => 'web',
                'parent' => 'Menu',
                'created_at' => '2024-05-15 15:32:30',
                'updated_at' => '2024-05-15 15:32:30',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Role create',
                'guard_name' => 'web',
                'parent' => 'Role',
                'created_at' => '2024-05-15 15:32:57',
                'updated_at' => '2024-05-15 15:32:57',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Role view',
                'guard_name' => 'web',
                'parent' => 'Role',
                'created_at' => '2024-05-15 15:32:57',
                'updated_at' => '2024-05-15 15:32:57',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Role edit',
                'guard_name' => 'web',
                'parent' => 'Role',
                'created_at' => '2024-05-15 15:32:57',
                'updated_at' => '2024-05-15 15:32:57',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Role destroy',
                'guard_name' => 'web',
                'parent' => 'Role',
                'created_at' => '2024-05-15 15:32:57',
                'updated_at' => '2024-05-15 15:32:57',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Role status',
                'guard_name' => 'web',
                'parent' => 'Role',
                'created_at' => '2024-05-15 15:32:57',
                'updated_at' => '2024-05-15 15:32:57',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Role restore',
                'guard_name' => 'web',
                'parent' => 'Role',
                'created_at' => '2024-05-15 15:32:57',
                'updated_at' => '2024-05-15 15:32:57',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Role delete',
                'guard_name' => 'web',
                'parent' => 'Role',
                'created_at' => '2024-05-15 15:32:57',
                'updated_at' => '2024-05-15 15:32:57',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Role print',
                'guard_name' => 'web',
                'parent' => 'Role',
                'created_at' => '2024-05-15 15:32:57',
                'updated_at' => '2024-05-15 15:32:57',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Role trash',
                'guard_name' => 'web',
                'parent' => 'Role',
                'created_at' => '2024-05-15 15:32:57',
                'updated_at' => '2024-05-15 15:32:57',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Users create',
                'guard_name' => 'web',
                'parent' => 'Users',
                'created_at' => '2024-05-15 15:33:40',
                'updated_at' => '2024-05-15 15:33:40',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Users view',
                'guard_name' => 'web',
                'parent' => 'Users',
                'created_at' => '2024-05-15 15:33:40',
                'updated_at' => '2024-05-15 15:33:40',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Users edit',
                'guard_name' => 'web',
                'parent' => 'Users',
                'created_at' => '2024-05-15 15:33:40',
                'updated_at' => '2024-05-15 15:33:40',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Users destroy',
                'guard_name' => 'web',
                'parent' => 'Users',
                'created_at' => '2024-05-15 15:33:40',
                'updated_at' => '2024-05-15 15:33:40',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'Users status',
                'guard_name' => 'web',
                'parent' => 'Users',
                'created_at' => '2024-05-15 15:33:41',
                'updated_at' => '2024-05-15 15:33:41',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Users restore',
                'guard_name' => 'web',
                'parent' => 'Users',
                'created_at' => '2024-05-15 15:33:41',
                'updated_at' => '2024-05-15 15:33:41',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Users delete',
                'guard_name' => 'web',
                'parent' => 'Users',
                'created_at' => '2024-05-15 15:33:41',
                'updated_at' => '2024-05-15 15:33:41',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'Users print',
                'guard_name' => 'web',
                'parent' => 'Users',
                'created_at' => '2024-05-15 15:33:41',
                'updated_at' => '2024-05-15 15:33:41',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'Users trash',
                'guard_name' => 'web',
                'parent' => 'Users',
                'created_at' => '2024-05-15 15:33:41',
                'updated_at' => '2024-05-15 15:33:41',
            ),
            36 => 
            array (
                'id' => 38,
                'name' => 'Dashboard view',
                'guard_name' => 'web',
                'parent' => 'Dashboard',
                'created_at' => '2024-06-10 00:08:54',
                'updated_at' => '2024-06-10 00:08:54',
            ),
            37 => 
            array (
                'id' => 39,
                'name' => 'Create Store create',
                'guard_name' => 'web',
                'parent' => 'Create Store',
                'created_at' => '2024-06-10 00:16:48',
                'updated_at' => '2024-06-10 00:16:48',
            ),
            38 => 
            array (
                'id' => 40,
                'name' => 'Manage Store view',
                'guard_name' => 'web',
                'parent' => 'Manage Store',
                'created_at' => '2024-06-10 00:17:29',
                'updated_at' => '2024-06-10 00:17:29',
            ),
            39 => 
            array (
                'id' => 41,
                'name' => 'Manage Store edit',
                'guard_name' => 'web',
                'parent' => 'Manage Store',
                'created_at' => '2024-06-10 00:17:29',
                'updated_at' => '2024-06-10 00:17:29',
            ),
            40 => 
            array (
                'id' => 42,
                'name' => 'Manage Store destroy',
                'guard_name' => 'web',
                'parent' => 'Manage Store',
                'created_at' => '2024-06-10 00:17:29',
                'updated_at' => '2024-06-10 00:17:29',
            ),
            41 => 
            array (
                'id' => 43,
                'name' => 'Manage Store status',
                'guard_name' => 'web',
                'parent' => 'Manage Store',
                'created_at' => '2024-06-10 00:17:29',
                'updated_at' => '2024-06-10 00:17:29',
            ),
            42 => 
            array (
                'id' => 44,
                'name' => 'Manage Store restore',
                'guard_name' => 'web',
                'parent' => 'Manage Store',
                'created_at' => '2024-06-10 00:17:29',
                'updated_at' => '2024-06-10 00:17:29',
            ),
            43 => 
            array (
                'id' => 45,
                'name' => 'Manage Store delete',
                'guard_name' => 'web',
                'parent' => 'Manage Store',
                'created_at' => '2024-06-10 00:17:29',
                'updated_at' => '2024-06-10 00:17:29',
            ),
            44 => 
            array (
                'id' => 46,
                'name' => 'Manage Store print',
                'guard_name' => 'web',
                'parent' => 'Manage Store',
                'created_at' => '2024-06-10 00:17:29',
                'updated_at' => '2024-06-10 00:17:29',
            ),
            45 => 
            array (
                'id' => 47,
                'name' => 'Manage Store trash',
                'guard_name' => 'web',
                'parent' => 'Manage Store',
                'created_at' => '2024-06-10 00:17:29',
                'updated_at' => '2024-06-10 00:17:29',
            ),
        ));
        
        
    }
}