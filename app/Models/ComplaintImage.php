<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintImage extends Model
{


    protected $fillable = ['complaint_id','file_path'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s',
        'updated_at' => 'datetime:Y-m-d\TH:i:s',
    ];


    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }



}
