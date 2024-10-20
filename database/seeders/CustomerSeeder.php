<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
                ['name' => 'John Doe', 'company' => 'Doe Inc.', 'phone' => '123456789', 'email' => 'john@example.com', 'country' => 'USA', 'status' => 1],
                ['name' => 'Jane Smith', 'company' => 'Smith Co.', 'phone' => '987654321', 'email' => 'jane@example.com', 'country' => 'Canada', 'status' => 1],
                ['name' => 'Alice Johnson', 'company' => 'Johnson LLC', 'phone' => '123123123', 'email' => 'alice@example.com', 'country' => 'UK', 'status' => 1],
                ['name' => 'Michael Brown', 'company' => 'Brown Enterprises', 'phone' => '555555555', 'email' => 'michael@example.com', 'country' => 'Australia', 'status' => 1],
                ['name' => 'Emily Davis', 'company' => 'Davis Corp.', 'phone' => '444444444', 'email' => 'emily@example.com', 'country' => 'New Zealand', 'status' => 1],
                ['name' => 'David Wilson', 'company' => 'Wilson Ltd.', 'phone' => '333333333', 'email' => 'david@example.com', 'country' => 'India', 'status' => 1],
                ['name' => 'Sarah Lee', 'company' => 'Lee & Co.', 'phone' => '222222222', 'email' => 'sarah@example.com', 'country' => 'Singapore', 'status' => 1],
                ['name' => 'Chris Martin', 'company' => 'Martin Industries', 'phone' => '111111111', 'email' => 'chris@example.com', 'country' => 'South Africa', 'status' => 1],
                ['name' => 'Jessica Taylor', 'company' => 'Taylor Solutions', 'phone' => '666666666', 'email' => 'jessica@example.com', 'country' => 'Germany', 'status' => 1],
                ['name' => 'Daniel Anderson', 'company' => 'Anderson Group', 'phone' => '777777777', 'email' => 'daniel@example.com', 'country' => 'France', 'status' => 1],
                ['name' => 'Laura Thomas', 'company' => 'Thomas Inc.', 'phone' => '888888888', 'email' => 'laura@example.com', 'country' => 'Italy', 'status' => 1],
                ['name' => 'James White', 'company' => 'White Enterprises', 'phone' => '999999999', 'email' => 'james@example.com', 'country' => 'Spain', 'status' => 1],
                ['name' => 'Linda Harris', 'company' => 'Harris LLC', 'phone' => '101010101', 'email' => 'linda@example.com', 'country' => 'Brazil', 'status' => 1]
            
            
            
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
