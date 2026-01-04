<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Admin extends  Authenticatable
{

    use HasRoles;

    protected $guard_name = 'admin-api';


    use HasFactory, Notifiable, HasApiTokens;

//    protected $guard_name = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'government_agency_id',
         'role',
    ];
    protected $hidden = [
        'password',

    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s',
        'updated_at' => 'datetime:Y-m-d\TH:i:s',
    ];


    public function agency()
    {
        return $this->belongsTo(GovernmentAgency::class, 'government_agency_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(ComplaintStatusHistory::class);
    }



}
