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
        Schema::create('meeting_agenda_rule_of_meeting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_agenda_id');
            $table->unsignedBigInteger('rule_of_meeting_id');
            $table->timestamps();

            $table->foreign('meeting_agenda_id')->references('id')->on('meeting_agendas')->onDelete('cascade');
            $table->foreign('rule_of_meeting_id')->references('id')->on('ruleof_meetings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_agenda_rule_of_meeting');
    }
};