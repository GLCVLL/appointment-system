<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'start_time', 'end_time', 'notes', 'is_deleted'];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
