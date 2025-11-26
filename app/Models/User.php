<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'blocked',
        'blocked_at',
        'missed_appointments_count',
        'missed_appointments_cycle',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'blocked_at' => 'datetime',
    ];

    // One to Many with Appointment
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Increment missed appointments counters
     */
    public function incrementMissedAppointment(): void
    {
        $this->missed_appointments_count++;
        $this->missed_appointments_cycle++;
        
        // Reset cycle when it reaches 3 and block the user
        if ($this->missed_appointments_cycle >= 3) {
            $this->missed_appointments_cycle = 0;
            $this->blocked = true;
            $this->blocked_at = now();
        }
        
        $this->save();
    }

    /**
     * Decrement missed appointments counters
     */
    public function decrementMissedAppointment(): void
    {
        if ($this->missed_appointments_count > 0) {
            $this->missed_appointments_count--;
        }
        
        if ($this->missed_appointments_cycle > 0) {
            $this->missed_appointments_cycle--;
        }
        
        $this->save();
    }
}
