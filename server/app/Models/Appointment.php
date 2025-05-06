<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $fillable = ['title', 'date', 'start', 'end', 'status', 'price', 'lesson_id'];

    function lesson(): BelongsTo {
        return $this->belongsTo(Lesson::class);
    }

    function booking(): HasOne {
        return $this->hasOne(Booking::class);
    }
}
