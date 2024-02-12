<?php

namespace Database\Seeders;

use App\Models\InvoiceEntity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvoiceEntity::insert([
            [
                'id' => '1',
                'name' => 'Jacey Sawayn',
                'gst_no' => '101',
                'address' => '92250 Kovacek Port',
                'area' => 'Sit sed occaecati corporis quisquam eaque totam dolor saepe itaque.',
                'city' => 'Bloomington',
                'state' => 'Illinois',
                'country' => 'India',
                'pincode' => '06382',
                'primary_name' => 'Ally Schiller',
                'primary_email' => 'your.email+fakedata61112@gmail.com',
                'primary_mobile' => '7894561231',
                'primary_designation' => 'Quas pariatur autem aperiam rem sint fuga.',
                'account_name' => 'Major Beatty',
                'account_number' => '553',
                'ifsc_code' => '67902-8796',
                'bank_name' => 'Isabell Morar',
                'branch' => 'Officia provident a laboriosam.',
                'description' => 'Eius quas rerum rerum in in maxime iure accusantium.',
                'status' => '1',
            ],
        ]);
    }
}
