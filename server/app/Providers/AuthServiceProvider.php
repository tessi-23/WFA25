<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //Gate::define('own-category', function (User $user, Category $category) {
            //return $user->id === $category->user_id;
        //});

        // tutor
        Gate::define('own-lesson', function (User $user, Lesson $lesson) {
            return $user->role === 'tutor' && $user->id === $lesson->tutor_id;
        });
        Gate::define('own-appointment', function (User $user, Appointment $appointment) {
            return $user->role === 'tutor' && $user->id === $appointment->lesson->tutor_id;
        });
        Gate::define('own-booking', function (User $user, Booking $booking) {
            return $user->role === 'tutor' && $user->id === $booking->appointment->lesson->tutor_id;
        });
    }
}
