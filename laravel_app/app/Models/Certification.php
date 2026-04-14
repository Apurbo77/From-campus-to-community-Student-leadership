<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $table = 'certification';
    protected $primaryKey = 'certification_id';
    public $timestamps = false;
    protected $guarded = [];

}
