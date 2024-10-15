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
        Schema::create('meeting_approval_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_approval_id');
            $table->unsignedBigInteger('meeting_agenda_section_id');
            $table->enum('approval_type', ['no_changes', 'with_changes']);
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('meeting_approval_id')->references('id')->on('meeting_approvals')->onDelete('cascade');
            $table->foreign('meeting_agenda_section_id')->references('id')->on('meeting_agenda_sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_approval_details');
    }
};
