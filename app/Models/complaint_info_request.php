<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class complaint_info_request extends Model
{
    protected $fillable = [
        'complaint_id',
        'admin_id',
        'request_text',
        'citizen_response',
        'attachment',
        'is_answered'
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
