<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventplanungTable extends Migration
{
    public function up()
    {
        Schema::create('eventplanung', function (Blueprint $table) {
            $table->id();
            $table->string('vorname_nachname', 255);
            $table->string('telefonnummer', 20)->nullable();
            $table->text('anfrage')->nullable()->default('Kein Kommentar');
            $table->dateTime('datum_uhrzeit')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status', ['in Bearbeitung', 'in Planung', 'Abgeschlossen'])->nullable()->default('in Planung');
            $table->text('summernote_content')->nullable();
            $table->text('team_verteilung')->nullable();
            $table->dateTime('datum_uhrzeit_event')->nullable();
            $table->string('ort', 255)->nullable();
            $table->integer('event_lead')->nullable();
            $table->string('event', 255)->nullable();
            $table->text('anmerkung')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eventplanung');
    }
}