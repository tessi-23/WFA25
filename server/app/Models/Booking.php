<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = ['status', 'comment', 'appointment_id', 'tutor_id', 'student_id'];

    function tutor(): BelongsTo {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    function student(): BelongsTo {
        return $this->belongsTo(User::class, 'student_id');
    }

    function appointment(): BelongsTo {
        return $this->belongsTo(Appointment::class);
    }
}
