<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'date', 'start_time', 'end_time', 'notes'];

    // One to Many with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Many to Many with Service
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

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
