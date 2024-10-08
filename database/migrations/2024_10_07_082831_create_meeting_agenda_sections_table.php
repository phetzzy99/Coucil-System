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
        Schema::create('meeting_agenda_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_agenda_id');
            $table->string('section_title');
            $table->timestamps();

            $table->foreign('meeting_agenda_id')->references('id')->on('meeting_agendas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_agenda_sections');
    }
};
