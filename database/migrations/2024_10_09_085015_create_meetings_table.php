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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_type_id');
            $table->unsignedBigInteger('committee_category_id');
            $table->unsignedBigInteger('meeting_format_id');
            $table->unsignedBigInteger('meeting_agenda_id');
            $table->unsignedBigInteger('rule_of_meeting_id');
            $table->unsignedBigInteger('regulation_meeting_id');
            $table->unsignedBigInteger('meeting_agenda_section_id');
            $table->unsignedBigInteger('meeting_agenda_lecture_id');
            $table->unsignedBigInteger('meeting_agenda_item_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('1')->comment('1=active, 0=inactive');
            $table->timestamps();

            $table->foreign('meeting_type_id')->references('id')->on('meeting_types')->onDelete('cascade');
            $table->foreign('committee_category_id')->references('id')->on('committee_categories')->onDelete('cascade');
            $table->foreign('meeting_format_id')->references('id')->on('meeting_formats')->onDelete('cascade');
            $table->foreign('meeting_agenda_id')->references('id')->on('meeting_agendas')->onDelete('cascade');
            $table->foreign('rule_of_meeting_id')->references('id')->on('ruleof_meetings')->onDelete('cascade');
            $table->foreign('regulation_meeting_id')->references('id')->on('regulation_meetings')->onDelete('cascade');
            $table->foreign('meeting_agenda_section_id')->references('id')->on('meeting_agenda_sections')->onDelete('cascade');
            $table->foreign('meeting_agenda_lecture_id')->references('id')->on('meeting_agenda_lectures')->onDelete('cascade');
            $table->foreign('meeting_agenda_item_id')->references('id')->on('meeting_agenda_items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
