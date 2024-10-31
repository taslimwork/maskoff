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
        Schema::create('strategy_types', function (Blueprint $table) {
            $table->id();
            $table->string('strategy_type');
            $table->string('slug');
            $table->unsignedBigInteger('strategy_id');
            $table->foreign('strategy_id')->references('id')->on('strategies')->onDelete('cascade');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategy_types');
    }
};
