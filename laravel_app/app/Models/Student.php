<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;



class Student extends Authenticatable
{
    protected $table = 'student';
    protected $primaryKey = 'student_id';
    public $timestamps = false;
    protected $guarded = [];

}
