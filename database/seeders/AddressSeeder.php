<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run()
    {
        $addresses = [
            ['number' => '123', 'street' => 'Main St', 'city' => 'New York', 'customer_id' => 1],
            ['number' => '456', 'street' => 'Elm St', 'city' => 'Los Angeles', 'customer_id' => 2],
            ['number' => '789', 'street' => 'Oak St', 'city' => 'Chicago', 'customer_id' => 3],
            ['number' => '101', 'street' => 'Pine St', 'city' => 'Houston', 'customer_id' => 4],
            ['number' => '202', 'street' => 'Maple St', 'city' => 'Phoenix', 'customer_id' => 5],
            ['number' => '303', 'street' => 'Cedar St', 'city' => 'Philadelphia', 'customer_id' => 6],
            ['number' => '404', 'street' => 'Birch St', 'city' => 'San Antonio', 'customer_id' => 7],
            ['number' => '505', 'street' => 'Spruce St', 'city' => 'San Diego', 'customer_id' => 8],
            ['number' => '606', 'street' => 'Willow St', 'city' => 'Dallas', 'customer_id' => 9],
            ['number' => '707', 'street' => 'Ash St', 'city' => 'San Jose', 'customer_id' => 10],
            ['number' => '808', 'street' => 'Fir St', 'city' => 'Austin', 'customer_id' => 11],
            ['number' => '909', 'street' => 'Poplar St', 'city' => 'Jacksonville', 'customer_id' => 12],
            ['number' => '111', 'street' => 'Chestnut St', 'city' => 'San Francisco', 'customer_id' => 13],
        ];

        foreach ($addresses as $address) {
            Address::create($address);
        }
    }
}
