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
        Schema::create('committee_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('committee_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['committee_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committee_user');
    }
};
