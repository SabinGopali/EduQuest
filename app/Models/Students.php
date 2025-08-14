<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inquiry;


class Students extends Authenticatable
{
    use HasFactory;
    
    // Additional properties and methods specific to the Student model
    
    protected $table = 'students'; // Specify the table name if it's different from the default naming convention
    protected $fillable = [
        'name', 'email','password', 'user_type','contact',
        'image',
        'passedyear',
        'previousschool',
        'gpa',
        'interest',
        'goal',
        'educationLevel',
    ];

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'student_id');
    }
}
