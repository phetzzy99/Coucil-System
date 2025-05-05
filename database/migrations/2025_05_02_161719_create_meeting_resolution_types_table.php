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
        Schema::create('meeting_resolution_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('committee_category_id')->constrained('committee_categories')->onDelete('cascade');
            $table->foreignId('meeting_type_id')->constrained('meeting_types')->onDelete('cascade');
            $table->string('meeting_no');
            $table->string('meeting_year', 4);
            $table->date('meeting_date');
            $table->string('name');
            $table->string('agenda_title');
            $table->text('resolution_text');
            $table->string('document')->nullable();
            $table->enum('task_status', ['completed', 'in_progress', 'not_started'])->default('not_started');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_resolution_types');
    }
};
