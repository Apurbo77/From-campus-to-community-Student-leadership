<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DFinancial extends Model
{
    protected $table = 'd_financial';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

}
