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
    protected $appends = [
        'total_borrowed',
        'total_returned',
        'in_use',
        'latest_condition',
    ];


    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'item_students')
            ->withPivot('issued_at', 'returned_at', 'notes')
            ->withTimestamps();
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function scopeGeneral($query)
    {
        return $query->whereNull('lab_id');
    }
    public function getTotalBorrowedAttribute()
    {
        return $this->transactions()->where('action', 'issue')->sum('quantity');
    }

    public function getTotalReturnedAttribute()
    {
        return $this->transactions()->where('action', 'return')->sum('quantity');
    }
    public function getInUseAttribute()
    {
        return max(0, $this->total_borrowed - $this->total_returned);
    }
    // Item.php
    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latest();
    }

    public function getLatestConditionAttribute()
    {
        return $this->latestTransaction?->condition ?? 'N/A';
    }

}
