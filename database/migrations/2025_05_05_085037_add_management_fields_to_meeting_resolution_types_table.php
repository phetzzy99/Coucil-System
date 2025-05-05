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
        Schema::table('meeting_resolution_types', function (Blueprint $table) {
            // เพิ่มฟิลด์ใหม่
            $table->foreignId('management_category_id')->nullable()->after('committee_category_id');
            $table->foreignId('management_keyword_id')->nullable()->after('management_category_id');

            // เพิ่ม foreign key constraints
            $table->foreign('management_category_id')->references('id')->on('management_categories')->onDelete('set null');
            $table->foreign('management_keyword_id')->references('id')->on('management_keywords')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meeting_resolution_types', function (Blueprint $table) {
            // ลบ foreign key constraints
            $table->dropForeign(['management_category_id']);
            $table->dropForeign(['management_keyword_id']);

            // ลบฟิลด์
            $table->dropColumn('management_category_id');
            $table->dropColumn('management_keyword_id');
        });
    }
};
