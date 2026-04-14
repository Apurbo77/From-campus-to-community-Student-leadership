<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonation extends Model
{
    protected $table = 'blood_donation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

}
