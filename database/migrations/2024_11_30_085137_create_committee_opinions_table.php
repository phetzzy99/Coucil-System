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
        Schema::create('committee_opinions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_agenda_lecture_id');
            $table->unsignedBigInteger('user_id');
            $table->text('opinion')->nullable();
            $table->enum('vote_type', ['approve', 'reject']);
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('meeting_agenda_lecture_id')->references('id')->on('meeting_agenda_lectures')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committee_opinions');
    }
};
