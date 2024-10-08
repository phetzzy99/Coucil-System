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
        Schema::create('meeting_agendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_type_id');
            $table->unsignedBigInteger('user_id');
            $table->string('meeting_agenda_title');
            $table->string('meeting_agenda_number');
            $table->string('meeting_agenda_year');
            $table->date('meeting_agenda_date');
            $table->time('meeting_agenda_time');
            $table->string('meeting_location');
            $table->text('description')->nullable();
            $table->string('status')->default('1')->comment('1=active, 0=inactive');
            $table->timestamps();

            $table->foreign('meeting_type_id')->references('id')->on('meeting_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_agendas');
    }
};
