<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model

{
    protected $fillable = [
        'actor_type',
        'actor_id',
        'action',
        'entity',
        'entity_id',
        'method',
        'url',
        'ip',
        'status_code',
        'success',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',

    ];
}
