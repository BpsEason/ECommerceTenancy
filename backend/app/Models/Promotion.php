<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'value',
        'start_date',
        'end_date',
        'rules' // **ENHANCEMENT: Added JSON rules attribute**
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'rules' => 'json'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
