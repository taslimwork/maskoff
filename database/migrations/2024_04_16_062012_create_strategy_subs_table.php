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
        Schema::create('strategy_subs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->index();

            $table->unsignedBigInteger('strategy_id');
            $table->foreign('strategy_id')->references('id')->on('strategies')->onDelete('cascade');

            $table->unsignedBigInteger('strategy_type_id');
            $table->foreign('strategy_type_id')->references('id')->on('strategy_types')->onDelete('cascade');

            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategy_subs');
    }
};
