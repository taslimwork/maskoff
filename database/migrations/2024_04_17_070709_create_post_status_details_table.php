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
        Schema::create('post_status_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_status_id');
            $table->foreign('post_status_id')->references('id')->on('post_statuses')->onDelete('cascade');
            $table->longText('post_contents')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_status_details');
    }
};
