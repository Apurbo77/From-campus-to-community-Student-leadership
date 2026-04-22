<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonationCamp extends Model
{
    protected $table = 'blood_donation_camp';
    protected $primaryKey = 'camp_id';
    public $timestamps = false;
    protected $guarded = [];

}
