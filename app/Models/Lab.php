<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    const ALLOWED_LAB_NAMES = [
        'Computer Lab',
        'Physics Lab',
        'Chemistry Lab',
        'Biology Lab'
    ];

    protected $fillable = ["name", "location", "description","status"];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
