<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'quantity_total',
        'quantity_available',
        'issued_once',
        'reorder_threshold',
        'lab_id',
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'item_students')
        ->withPivot('issued_at','returned_at','notes')
        ->withTimestamps();
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
