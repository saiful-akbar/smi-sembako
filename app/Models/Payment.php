<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'method',
        'amount',
        'provider',
        'provider_ref',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
