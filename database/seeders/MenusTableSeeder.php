<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menus')->delete();
        
        \DB::table('menus')->insert(array (
            0 => 
            array (
                'id' => 1,
                'position' => 'cms',
                'label_id' => 8,
                'name' => 'Developer Option',
                'name_bn' => NULL,
                'system_name' => NULL,
                'route' => NULL,
                'icon' => 'fa fa-layer',
                'status' => 1,
                'type' => 1,
                'create_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2024-04-17 17:54:30',
                'updated_at' => '2024-04-17 17:54:30',
            ),
        ));
        
        
    }
}