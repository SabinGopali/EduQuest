<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry', function (Blueprint $table) {
            $table->id();

            // Foreign key for student
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->cascadeOnUpdate();

            // Foreign key for college
            $table->foreignId('college_id')->constrained('colleges')->nullable()->cascadeOnDelete()->cascadeOnUpdate();

            // Foreign key for course detail
            $table->foreignId('coursedetail_id')->constrained('courseDetail')->cascadeOnDelete()->cascadeOnUpdate();

            // Additional fields
            $table->longText('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiry');
    }
}
