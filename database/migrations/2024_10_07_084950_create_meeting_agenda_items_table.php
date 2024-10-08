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
        Schema::create('meeting_agenda_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_agenda_id');
            $table->unsignedBigInteger('meeting_agenda_section_id');
            $table->unsignedBigInteger('meeting_agenda_lecture_id');
            $table->string('item_title');
            $table->longText('content')->nullable();
            $table->string('pdf')->nullable();
            $table->timestamps();

            $table->foreign('meeting_agenda_id')->references('id')->on('meeting_agendas')->onDelete('cascade');
            $table->foreign('meeting_agenda_section_id')->references('id')->on('meeting_agenda_sections')->onDelete('cascade');
            $table->foreign('meeting_agenda_lecture_id')->references('id')->on('meeting_agenda_lectures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_agenda_items');
    }
};
