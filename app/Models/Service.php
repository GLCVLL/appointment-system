<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $fillable = ['category_id', 'name', 'duration', 'is_available'];


    //*** UTILITIES ***//
    /**
     * Format a date field
     */
    public function getDate($date_field, $format = 'd/m/y H:i')
    {
        return Carbon::create($this->$date_field)
            ->format($format);
    }
}
