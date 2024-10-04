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
        Schema::create('meeting_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('committee_category_id');
            $table->unsignedBigInteger('meeting_type_id');
            $table->string('title');
            $table->string('meeting_no');
            $table->date('date');
            $table->time('time');
            $table->string('year');
            $table->string('pdf')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('committee_category_id')->references('id')->on('committee_categories')->onDelete('cascade');
            $table->foreign('meeting_type_id')->references('id')->on('meeting_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_reports');
    }
};
