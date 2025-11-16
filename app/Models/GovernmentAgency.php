<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentAgency extends Model
{
    public function complaint()
    {
        return $this->hasMany(complaint::class);
    }
    public function employee()
    {
        return $this->hasMany(employee::class);
    }
}
