<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "registration_number",  
        "class",
        "gender",
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class,"item_students")
        ->withPivot('issued_at','returned_at','notes')
        ->withTimestamps();
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);

    }
}
