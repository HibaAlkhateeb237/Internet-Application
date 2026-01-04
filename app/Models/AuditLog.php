<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'entity',
        'entity_id',
        'method',
        'url',
        'ip',
        'status_code',
        'success',
        'payload',
        'actor_type',
        'actor_id',
    ];

    /**

     *
     */
    protected $casts = [
        'payload' => 'array',
        'success' => 'boolean',
    ];
    public function actor()
    {
        return $this->morphTo();
    }
}
