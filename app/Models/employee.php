<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
   public function HistoryStatus()
    {
        return $this->hasMany(History_status::class);
    }


}
