<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; use Illuminate\Database\Eloquent\Model;
class CustomerPoint extends Model { use HasFactory; protected $fillable=['customer_id','points','reason']; public function customer(){ return $this->belongsTo(Customer::class);} }
