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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('user_type')->default('students');
            $table->string('contact'); // Add contact column
            $table->integer('passedyear'); // Add passedyear column
            $table->string('previousschool'); // Add previousschool column
            $table->decimal('gpa', 3, 2); // Add gpa column
            $table->text('interest'); // Add interest column
            $table->text('goal'); // Add goal column
            $table->string('educationLevel'); // Add goal column
            $table->string('image')->nullable(); // Add image column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
