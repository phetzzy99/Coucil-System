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
        Schema::create('meeting_resolutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('committee_category_id')->constrained('committee_categories')->onDelete('cascade');
            $table->foreignId('meeting_type_id')->constrained('meeting_types')->onDelete('cascade');
            $table->foreignId('meeting_agenda_id')->constrained('meeting_agendas')->onDelete('cascade');
            $table->foreignId('meeting_agenda_section_id')->constrained('meeting_agenda_sections')->onDelete('cascade');
            $table->string('proposer')->nullable(); // ผู้เสนอวาระ
            $table->string('document')->nullable(); // เอกสารประกอบการประชุม
            $table->text('resolution_text');
            $table->date('resolution_date');
            $table->enum('resolution_status', ['approved', 'rejected', 'pending'])->default('pending');

            // ส่วนการดำเนินการที่ได้รับมอบหมาย
            $table->string('task_title')->nullable(); // เรื่อง
            $table->string('responsible_person')->nullable(); // ผู้รับผิดชอบ
            $table->enum('task_status', ['completed', 'in_progress', 'not_started'])->default('not_started'); // ผลการดำเนินงาน
            $table->date('report_date')->nullable(); // วันที่รายงานผล

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_resolutions');
    }
};
