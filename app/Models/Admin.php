<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasFactory;
    
    // Additional properties and methods specific to the Admin model
    
    protected $table = 'admins'; // Specify the table name if it's different from the default naming convention

    protected $fillable = [
        'name', 'email','password', 'user_type',
    ];
}
