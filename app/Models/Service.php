<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Many to Many with Appointment
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class);
    }

    // One to Many with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $fillable = ['name', 'duration', 'is_available'];
}
