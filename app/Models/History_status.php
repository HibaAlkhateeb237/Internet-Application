<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History_status extends Model
{
//    public function employee()
//    {
//        return $this->belongsTo(employee::class);
//    }
    public function complaint()
    {
        return $this->belongsTo(complaint::class);
    }
}
