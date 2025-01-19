<?php

namespace Database\Seeders;

use App\Models\Rabatt;
use Illuminate\Database\Seeder;

class RabattSeeder extends Seeder
{
    public function run()
    {
        Rabatt::create([
            'display_name' => 'Test Rabatt',
            'description' => 'Test Description',
            'rabatt_percent' => 10,
            'created_by' => 1
        ]);
    }
}