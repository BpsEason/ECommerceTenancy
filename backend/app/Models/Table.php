<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'number', 'capacity', 'status'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
