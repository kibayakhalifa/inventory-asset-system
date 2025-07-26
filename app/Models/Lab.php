<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    const TYPES = [
        'Computer Lab',
        'Physics Lab',
        'Chemistry Lab',
        'Biology Lab',
    ];

    protected $fillable = ["name", "location", "description"];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
