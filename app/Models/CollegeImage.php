<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 
        'college_id',
        'path',
    ];
}
