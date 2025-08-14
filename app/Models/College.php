<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Authenticatable
{
    use HasFactory;

    // Additional properties and methods specific to the College model

    protected $table = 'colleges'; // Specify the table name if it's different from the default naming convention
    protected $fillable = [
        'name', 'email','password', 'address', 'latitude', 'longitude', 'user_type','phone',
        'contact',
        'description',
        'logo',
        'gallery.*',
    ];

    public function images()
    {
        return $this->hasMany(CollegeImage::class);
    }

    public function courseDetails()
    {
        return $this->hasMany(CourseDetail::class, 'college_id');
    }
}
