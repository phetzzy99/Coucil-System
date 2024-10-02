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
        Schema::create('ruleof_meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('rule_category_id');
            $table->string('title');
            $table->string('rule_meeting_pdf')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('1')->comment('1=active, 0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruleof_meetings');
    }
};
