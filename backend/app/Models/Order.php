<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'status',
        'total_amount',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('tenant_id', function (Builder $builder) {
            if ($tenantId = request()->attributes->get('tenant_id')) {
                $builder->where('tenant_id', $tenantId);
            }
        });
    }
}
