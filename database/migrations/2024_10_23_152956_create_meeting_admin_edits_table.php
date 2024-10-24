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
        Schema::create('meeting_admin_edits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_agenda_id');
            $table->unsignedBigInteger('section_id');
            $table->text('comment');
            $table->unsignedBigInteger('edited_by');
            $table->timestamp('edited_at');
            $table->timestamps();

            $table->foreign('meeting_agenda_id')->references('id')->on('meeting_agendas')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('meeting_agenda_sections')->onDelete('cascade');
            $table->foreign('edited_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_admin_edits');
    }
};
