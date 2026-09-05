<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'start_at',
        'end_at',
        'config',
        'active'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'config' => 'array',
        'active' => 'boolean'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_products');
    }
}
