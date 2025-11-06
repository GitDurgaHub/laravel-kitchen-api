<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'items', 'pickup_time', 'is_vip', 'status', 'completed_at'
    ];

    protected $casts = [
        'items' => 'array',
        'pickup_time' => 'datetime',
        'is_vip' => 'boolean',
        'completed_at' => 'datetime',
    ];
}