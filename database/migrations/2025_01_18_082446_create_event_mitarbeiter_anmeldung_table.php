<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventMitarbeiterAnmeldungTable extends Migration
{
    public function up()
    {
        Schema::create('event_mitarbeiter_anmeldung', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('eventplanung')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_mitarbeiter_anmeldung');
    }
}