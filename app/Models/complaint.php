<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class complaint extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function GovernmentAgency()
    {
        return $this->belongsTo(GovernmentAgency::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function HistoryStatus()
    {
        return $this->hasMany(History_status::class);
    }
}
