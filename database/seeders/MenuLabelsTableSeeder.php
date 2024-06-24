<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuLabelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_labels')->delete();
        
        \DB::table('menu_labels')->insert(array (
            0 => 
            array (
                'id' => 7,
                'label_name' => 'Dashboard',
                'label_name_bn' => 'ড্যাশবোর্ড',
                'status' => 1,
                'type' => 'cms',
                'deleted_at' => NULL,
                'create_by' => 3,
                'created_at' => '2024-06-09 23:55:29',
                'updated_at' => '2024-06-09 23:55:29',
            ),
            1 => 
            array (
                'id' => 8,
                'label_name' => 'Developer Option',
                'label_name_bn' => 'ডেভেলপার অপশান',
                'status' => 1,
                'type' => 'cms',
                'deleted_at' => NULL,
                'create_by' => 1,
                'created_at' => '2024-04-16 10:24:59',
                'updated_at' => '2024-04-18 15:37:06',
            ),
            2 => 
            array (
                'id' => 9,
                'label_name' => 'Test',
                'label_name_bn' => 'Test',
                'status' => 1,
                'type' => 'cms',
                'deleted_at' => '2024-05-14',
                'create_by' => 3,
                'created_at' => '2024-05-14 23:52:35',
                'updated_at' => '2024-05-14 23:58:12',
            ),
            3 => 
            array (
                'id' => 11,
                'label_name' => 'Store',
                'label_name_bn' => 'স্টোর',
                'status' => 1,
                'type' => 'cms',
                'deleted_at' => NULL,
                'create_by' => 3,
                'created_at' => '2024-06-10 00:13:27',
                'updated_at' => '2024-06-10 00:13:27',
            ),
        ));
        
        
    }
}