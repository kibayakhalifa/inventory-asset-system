<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "item_id",
        "user_id",
        "student_id",
        "lab_id",
        "action",// to indicate  whether it is for issued or returned
        "quantity",
    ];
    protected $dates = [
        "created_at",
        "updated_at",
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}
