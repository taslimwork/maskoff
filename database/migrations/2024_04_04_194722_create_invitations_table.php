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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [1, 2])->comment('1 => invitation, 2 => join request');
            $table->unsignedBigInteger('sender')->nullable();
            $table->foreign('sender')->references('id')->on('users');
            $table->unsignedBigInteger('reciver')->nullable();
            $table->foreign('reciver')->references('id')->on('users');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->enum('action', ['0','1', '2'])->default(0)->nullable()->comment('0 => pending, 1 => accept, 2 => reject');
            $table->unsignedBigInteger('action_attempt_by')->nullable();
            $table->foreign('action_attempt_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
