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
        Schema::table('coursedetail', function (Blueprint $table) {
            $table->decimal('tuition_fee', 10, 2)->nullable()->after('description');
            $table->integer('seats')->nullable()->after('tuition_fee');
            $table->longText('eligibility')->nullable()->after('seats');
            $table->longText('admission_process')->nullable()->after('eligibility');
            $table->longText('placement')->nullable()->after('admission_process');
            $table->longText('scholarship')->nullable()->after('placement');
            $table->boolean('hostel')->nullable()->after('scholarship');
            $table->date('application_deadline')->nullable()->after('hostel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coursedetail', function (Blueprint $table) {
            $table->dropColumn([
                'tuition_fee',
                'seats',
                'eligibility',
                'admission_process',
                'placement',
                'scholarship',
                'hostel',
                'application_deadline',
            ]);
        });
    }
};