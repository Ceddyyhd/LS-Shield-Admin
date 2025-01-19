<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnfragenTable extends Migration
{
    public function up()
    {
        Schema::create('anfragen', function (Blueprint $table) {
            $table->id();
            $table->string('vorname_nachname');
            $table->string('telefonnummer');
            $table->text('anfrage');
            $table->timestamp('datum_uhrzeit')->useCurrent();
            $table->string('erstellt_von');
            $table->string('status')->default('Eingetroffen');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anfragen');
    }
}