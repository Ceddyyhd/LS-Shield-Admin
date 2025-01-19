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
    Schema::create('deckels', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vehicle_id')->constrained();
        $table->string('notiz');
        $table->decimal('betrag', 10, 2);
        $table->foreignId('erstellt_von')->constrained('users');
        $table->string('location');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deckels');
    }
};
