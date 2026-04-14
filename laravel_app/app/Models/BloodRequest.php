<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    protected $table = 'blood_request';
    protected $primaryKey = 'req_id';
    public $timestamps = false;
    protected $guarded = [];

}
