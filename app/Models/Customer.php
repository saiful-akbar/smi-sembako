<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; use Illuminate\Database\Eloquent\Model;
class Customer extends Model { use HasFactory; protected $fillable=['name','email','phone','member_code']; public function points(){ return $this->hasMany(CustomerPoint::class); } public function getTotalPointsAttribute(){ return (int) $this->points()->sum('points'); } }
