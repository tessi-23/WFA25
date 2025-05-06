<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = ['title', 'description', 'tutor_id', 'category_id'];

    function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    function appointments(): HasMany {
        return $this->hasMany(Appointment::class);
    }

    function tutor(): BelongsTo {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
