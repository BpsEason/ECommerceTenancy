<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'name', 'description', 'price', 'stock'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
