<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'student_id',
        'coursedetail_id',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function courseDetail()
    {
        return $this->belongsTo(CourseDetail::class, 'coursedetail_id');
    }
}
