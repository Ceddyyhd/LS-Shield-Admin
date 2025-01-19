<?php

namespace Database\Seeders;

use App\Models\VacationType;
use Illuminate\Database\Seeder;

class VacationTypeSeeder extends Seeder
{
    public function run()
    {
        VacationType::create(['name' => 'Urlaub', 'color' => '#28a745']);
        VacationType::create(['name' => 'Krank', 'color' => '#dc3545']);
        VacationType::create(['name' => 'Sonderurlaub', 'color' => '#ffc107']);
    }
}