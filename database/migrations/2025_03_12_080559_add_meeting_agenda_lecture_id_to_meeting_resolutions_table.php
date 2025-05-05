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
        Schema::table('meeting_resolutions', function (Blueprint $table) {
            $table->foreignId('meeting_agenda_lecture_id')->nullable()->after('meeting_agenda_section_id')->constrained('meeting_agenda_lectures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meeting_resolutions', function (Blueprint $table) {
            $table->dropForeign(['meeting_agenda_lecture_id']);
            $table->dropColumn('meeting_agenda_lecture_id');
        });
    }
};
