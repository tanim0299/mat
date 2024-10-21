<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'item_id' => 2,
                'category_name' => 'Feeding',
                'category_name_bn' => 'ফিডিং',
                'status' => 1,
                'deleted_at' => NULL,
                'create_by' => 3,
                'created_at' => '2024-10-20 14:26:40',
                'updated_at' => '2024-10-21 13:29:11',
            ),
            1 => 
            array (
                'id' => 2,
                'item_id' => 2,
                'category_name' => 'Safety',
                'category_name_bn' => 'সেফটি',
                'status' => 1,
                'deleted_at' => NULL,
                'create_by' => 3,
                'created_at' => '2024-10-21 13:27:32',
                'updated_at' => '2024-10-21 13:51:20',
            ),
        ));
        
        
    }
}