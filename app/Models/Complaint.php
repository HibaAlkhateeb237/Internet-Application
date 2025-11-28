<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;




class Complaint extends Model
{
    protected $fillable = [
        'user_id','government_agency_id','reference_number','status',
        'title','description','location',
        'locked_by_employee_id','locked_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s',
        'updated_at' => 'datetime:Y-m-d\TH:i:s',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agency()
    {
        return $this->belongsTo(GovernmentAgency::class, 'government_agency_id');
    }

    public function images()
    {
        return $this->hasMany(ComplaintImage::class);
    }

    public function history()
    {
        return $this->hasMany(ComplaintStatusHistory::class);
    }
}

