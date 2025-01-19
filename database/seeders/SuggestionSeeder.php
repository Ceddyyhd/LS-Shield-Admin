<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Suggestion;

class SuggestionSeeder extends Seeder
{
    public function run()
    {
        Suggestion::create([
            'user_id' => 1,
            'title' => 'Test Vorschlag',
            'description' => 'Test Beschreibung',
            'area' => 'IT',
            'status' => 'pending'
        ]);
    }
}