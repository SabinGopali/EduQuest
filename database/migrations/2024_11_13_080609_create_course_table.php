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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('stream'); // e.g., Science, Commerce, etc.
            $table->string('subStream'); // e.g., Physics, Math, etc.
            $table->string('name'); // Name of the course
            $table->string('shortName'); // Short name of the course
            $table->longText('description'); // Course description
            $table->string('gpa_limit', 5, 2); // GPA limit for eligibility
            $table->string('duration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
