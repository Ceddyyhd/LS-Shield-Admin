<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('umail')->unique()->after('email');
            $table->string('nummer')->after('name');
            $table->string('kontonummer')->after('nummer');
            $table->unsignedBigInteger('role_id')->after('remember_token');
            $table->unsignedBigInteger('rank_last_changed_by')->after('role_id');
            $table->string('profile_image')->nullable()->after('rank_last_changed_by');
            $table->boolean('gekuendigut')->default(false)->after('profile_image');
            $table->boolean('admin_bereich')->default(false)->after('gekuendigut');
            $table->boolean('bewerber')->default(false)->after('admin_bereich');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'umail', 'nummer', 'kontonummer', 'role_id', 'rank_last_changed_by',
                'profile_image', 'gekuendigut', 'admin_bereich', 'bewerber'
            ]);
        });
    }
};