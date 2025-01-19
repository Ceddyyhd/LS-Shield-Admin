<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            // User Management Permissions
            [
                'name' => 'view_users',
                'description' => 'Permission to view users',
                'display_name' => 'View Users',
                'bereich' => 1,
            ],
            [
                'name' => 'edit_users',
                'description' => 'Permission to edit users',
                'display_name' => 'Edit Users',
                'bereich' => 1,
            ],
            [
                'name' => 'delete_users',
                'description' => 'Permission to delete users',
                'display_name' => 'Delete Users',
                'bereich' => 1,
            ],
            [
                'name' => 'create_users',
                'description' => 'Permission to create users',
                'display_name' => 'Create Users',
                'bereich' => 1,
            ],

            // Role Management Permissions
            [
                'name' => 'view_roles',
                'description' => 'Permission to view roles',
                'display_name' => 'View Roles',
                'bereich' => 2,
            ],
            [
                'name' => 'edit_roles',
                'description' => 'Permission to edit roles',
                'display_name' => 'Edit Roles',
                'bereich' => 2,
            ],
            [
                'name' => 'delete_roles',
                'description' => 'Permission to delete roles',
                'display_name' => 'Delete Roles',
                'bereich' => 2,
            ],
            [
                'name' => 'create_roles',
                'description' => 'Permission to create roles',
                'display_name' => 'Create Roles',
                'bereich' => 2,
            ],

            // Employee Management Permissions
            [
                'name' => 'view_employee',
                'description' => 'Permission to view employees',
                'display_name' => 'View Employees',
                'bereich' => 3,
            ],
            [
                'name' => 'edit_employee',
                'description' => 'Permission to edit employees',
                'display_name' => 'Edit Employees',
                'bereich' => 3,
            ],
            [
                'name' => 'delete_employee',
                'description' => 'Permission to delete employees',
                'display_name' => 'Delete Employees',
                'bereich' => 3,
            ],
            [
                'name' => 'create_employee',
                'description' => 'Permission to create employees',
                'display_name' => 'Create Employees',
                'bereich' => 3,
            ],

            // Document Management Permissions
            [
                'name' => 'view_documents',
                'description' => 'Permission to view documents',
                'display_name' => 'View Documents',
                'bereich' => 4,
            ],
            [
                'name' => 'edit_documents',
                'description' => 'Permission to edit documents',
                'display_name' => 'Edit Documents',
                'bereich' => 4,
            ],
            [
                'name' => 'delete_documents',
                'description' => 'Permission to delete documents',
                'display_name' => 'Delete Documents',
                'bereich' => 4,
            ],
            [
                'name' => 'create_documents',
                'description' => 'Permission to create documents',
                'display_name' => 'Create Documents',
                'bereich' => 4,
            ],

            // Equipment Management Permissions
            [
                'name' => 'view_equipment',
                'description' => 'Permission to view equipment',
                'display_name' => 'View Equipment',
                'bereich' => 5,
            ],
            [
                'name' => 'edit_equipment',
                'description' => 'Permission to edit equipment',
                'display_name' => 'Edit Equipment',
                'bereich' => 5,
            ],
            [
                'name' => 'delete_equipment',
                'description' => 'Permission to delete equipment',
                'display_name' => 'Delete Equipment',
                'bereich' => 5,
            ],
            [
                'name' => 'create_equipment',
                'description' => 'Permission to create equipment',
                'display_name' => 'Create Equipment',
                'bereich' => 5,
            ],

            // Notes Management Permissions
            [
                'name' => 'view_notes',
                'description' => 'Permission to view notes',
                'display_name' => 'View Notes',
                'bereich' => 6,
            ],
            [
                'name' => 'edit_notes',
                'description' => 'Permission to edit notes',
                'display_name' => 'Edit Notes',
                'bereich' => 6,
            ],
            [
                'name' => 'delete_notes',
                'description' => 'Permission to delete notes',
                'display_name' => 'Delete Notes',
                'bereich' => 6,
            ],
            [
                'name' => 'create_notes',
                'description' => 'Permission to create notes',
                'display_name' => 'Create Notes',
                'bereich' => 6,
            ],

            // General Permissions
            [
                'name' => 'view_dashboard',
                'description' => 'Permission to view dashboard',
                'display_name' => 'View Dashboard',
                'bereich' => 7,
            ],
            [
                'name' => 'manage_settings',
                'description' => 'Permission to manage settings',
                'display_name' => 'Manage Settings',
                'bereich' => 7,
            ],
        ]);
    }
}