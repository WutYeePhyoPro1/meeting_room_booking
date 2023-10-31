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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('duration');
            $table->string('title');
            $table->integer('reason_id');
            $table->integer('user_id');
            $table->longText('remark',256)->nullable();
            $table->integer('extend_status')->nullable();
            $table->string('extended_duration')->nullable();
            $table->timestamp('extended_time')->nullable();
            $table->time('original_start')->nullable();
            $table->time('original_end')->nullable();
            $table->integer('status')->default(0);
            $table->time('finished_time')->nullable();
            $table->integer('noti')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
