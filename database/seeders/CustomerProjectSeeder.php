<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerProjectSeeder extends Seeder
{
    public function run()
    {
        $customerProjects = [
            ['customer_id' => 1, 'project_id' => 1],
            ['customer_id' => 2, 'project_id' => 2],
            ['customer_id' => 3, 'project_id' => 3],
            ['customer_id' => 4, 'project_id' => 4],
            ['customer_id' => 5, 'project_id' => 5],
            ['customer_id' => 6, 'project_id' => 6],
            ['customer_id' => 7, 'project_id' => 7],
            ['customer_id' => 8, 'project_id' => 8],
            ['customer_id' => 9, 'project_id' => 9],
            ['customer_id' => 10, 'project_id' => 10],
            ['customer_id' => 11, 'project_id' => 11],
            ['customer_id' => 12, 'project_id' => 12],
            ['customer_id' => 13, 'project_id' => 13],
        ];

        // Insert the data into the pivot table 'customer_project'
        DB::table('customer_project')->insert($customerProjects);
    }
}
