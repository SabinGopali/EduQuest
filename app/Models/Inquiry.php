<?php

namespace App\Models;
use App\Models\Students;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;
    protected $table = 'inquiry';

    protected $fillable = [
        'student_id',
        'college_id',  // Corrected to match the table name
        'coursedetail_id',
        'inquirydate',
        'message',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id');  // Updated for the 'colleges' table
    }

    public function courseDetail()
    {
        return $this->belongsTo(CourseDetail::class, 'coursedetail_id');
    }
}
