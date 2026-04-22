<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FDonation extends Model
{
    protected $table = 'f_donation';
    protected $primaryKey = 'f_id';
    public $timestamps = false;
    protected $guarded = [];

}
