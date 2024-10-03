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
        Schema::create('regulation_meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('regulation_category_id');
            $table->string('regulation_title');
            $table->string('regulation_pdf')->nullable();
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
        Schema::dropIfExists('regulation_meetings');
    }
};
