<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DBlood extends Model
{
    protected $table = 'd_blood';
    protected $primaryKey = 'student_id';
    public $timestamps = false;
    protected $guarded = [];

}
