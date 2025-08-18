<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('coursedetail_id');
            $table->string('status')->default('booked');
            $table->timestamps();

            $table->unique(['student_id', 'coursedetail_id'], 'student_coursedetail_unique');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('coursedetail_id')->references('id')->on('coursedetail')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};