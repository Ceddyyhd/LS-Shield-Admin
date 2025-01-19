<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->string('model');
        $table->string('license_plate')->unique();
        $table->string('location');
        $table->date('next_inspection');
        $table->text('notes')->nullable();
        $table->boolean('decommissioned')->default(false);
        $table->boolean('turbo_tuning')->default(false);
        $table->boolean('engine_tuning')->default(false);
        $table->boolean('transmission_tuning')->default(false);
        $table->boolean('brake_tuning')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
