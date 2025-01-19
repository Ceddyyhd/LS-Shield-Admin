<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionBereicheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permission_bereiche')->insert([
            [
                'id' => 1,
                'name' => 'mitarbeiter',
                'display_name' => 'Mitarbeiter Bereich',
            ],
            [
                'id' => 2,
                'name' => 'leitung',
                'display_name' => 'Leitungs Bereich',
            ],
            [
                'id' => 3,
                'name' => 'dokumente',
                'display_name' => 'Dokumente Bereich',
            ],
            [
                'id' => 4,
                'name' => 'ausrüstung',
                'display_name' => 'Ausrüstung Bereich',
            ],
            [
                'id' => 5,
                'name' => 'notizen',
                'display_name' => 'Notizen Bereich',
            ],
            [
                'id' => 6,
                'name' => 'dashboard',
                'display_name' => 'Dashboard Bereich',
            ],
            [
                'id' => 7,
                'name' => 'einstellungen',
                'display_name' => 'Einstellungen Bereich',
            ],
        ]);
    }
}