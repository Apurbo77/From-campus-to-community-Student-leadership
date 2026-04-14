<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    protected $table = 'training_session';
    protected $primaryKey = 'session_id';
    public $timestamps = false;
    protected $guarded = [];

}
