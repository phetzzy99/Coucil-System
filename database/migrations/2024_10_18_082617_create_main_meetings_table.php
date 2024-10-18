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
        Schema::create('main_meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('meeting_date');
            $table->unsignedBigInteger('meeting_type_id');
            $table->unsignedBigInteger('committee_category_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('meeting_type_id')->references('id')->on('meeting_types')->onDelete('cascade');
            $table->foreign('committee_category_id')->references('id')->on('committee_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_meetings');
    }
};
