<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;
    protected $guarded = [];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
