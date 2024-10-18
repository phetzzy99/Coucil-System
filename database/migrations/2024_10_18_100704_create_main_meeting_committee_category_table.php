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
        Schema::create('main_meeting_committee_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_meeting_id');
            $table->unsignedBigInteger('committee_category_id');
            $table->timestamps();

            $table->foreign('main_meeting_id', 'mm_cc_main_meeting_id_foreign')
                ->references('id')
                ->on('main_meetings')
                ->onDelete('cascade');

            $table->foreign('committee_category_id', 'mm_cc_committee_category_id_foreign')
                ->references('id')
                ->on('committee_categories')
                ->onDelete('cascade');

            $table->unique(['main_meeting_id', 'committee_category_id'], 'mm_cc_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_meeting_committee_category');
    }
};
