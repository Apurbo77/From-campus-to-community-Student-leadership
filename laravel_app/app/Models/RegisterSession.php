<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterSession extends Model
{
    protected $table = 'register_session';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

}
