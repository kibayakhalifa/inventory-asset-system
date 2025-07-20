<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStudent extends Model
{
    use HasFactory;

    protected $table = "item_students";

    protected $fillable = [
        "item_id", "student_id", "issued_at", "returned_at", "notes",
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
