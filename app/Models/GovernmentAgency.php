<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentAgency extends Model
{


    protected $fillable = ['name','address','phone','description'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s',
        'updated_at' => 'datetime:Y-m-d\TH:i:s',
    ];


    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }




}
