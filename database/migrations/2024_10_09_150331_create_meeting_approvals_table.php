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
        Schema::create('meeting_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_id');
            $table->unsignedBigInteger('meeting_type_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('committee_category_id');
            $table->unsignedBigInteger('meeting_format_id');
            $table->unsignedBigInteger('meeting_agenda_id');
            $table->unsignedBigInteger('rule_of_meeting_id');
            $table->unsignedBigInteger('regulation_meeting_id');
            $table->unsignedBigInteger('meeting_agenda_lecture_id');
            $table->unsignedBigInteger('meeting_agenda_section_id');
            $table->unsignedBigInteger('meeting_agenda_items_id');
            $table->boolean('is_approved_without_changes');
            $table->text('changes')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign(['meeting_id'], 'meeting_approvals_meeting_id_foreign')->references('id')->on('meetings')->onDelete('cascade');
            $table->foreign(['meeting_type_id'], 'meeting_approvals_meeting_type_id_foreign')->references('id')->on('meeting_types')->onDelete('cascade');
            $table->foreign(['user_id'], 'meeting_approvals_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
            $table->foreign(['committee_category_id'], 'meeting_approvals_committee_category_id_foreign')->references('id')->on('committee_categories')->onDelete('cascade');
            $table->foreign(['meeting_format_id'], 'meeting_approvals_meeting_format_id_foreign')->references('id')->on('meeting_formats')->onDelete('cascade');
            $table->foreign(['meeting_agenda_id'], 'meeting_approvals_meeting_agenda_id_foreign')->references('id')->on('meeting_agendas')->onDelete('cascade');
            $table->foreign(['rule_of_meeting_id'], 'meeting_approvals_rule_of_meeting_id_foreign')->references('id')->on('ruleof_meetings')->onDelete('cascade');
            $table->foreign(['regulation_meeting_id'], 'meeting_approvals_regulation_meeting_id_foreign')->references('id')->on('regulation_meetings')->onDelete('cascade');
            $table->foreign(['meeting_agenda_lecture_id'], 'meeting_approvals_meeting_agenda_lecture_id_foreign')->references('id')->on('meeting_agenda_lectures')->onDelete('cascade');
            $table->foreign(['meeting_agenda_section_id'], 'meeting_approvals_meeting_agenda_section_id_foreign')->references('id')->on('meeting_agenda_sections')->onDelete('cascade');
            $table->foreign(['meeting_agenda_items_id'], 'meeting_approvals_meeting_agenda_items_id_foreign')->references('id')->on('meeting_agenda_items')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('meeting_approvals');
    }
};
