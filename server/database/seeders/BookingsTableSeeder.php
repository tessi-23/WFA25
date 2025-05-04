<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bookings')->insert([
            // Booking für Vue Termin 1
            [
                'status' => 'rejected',
                'comment' => 'Geht auch der 10.Mai?',
                'appointment_id' => 1, // Vue Termin 1
                'tutor_id' => 1,
                'student_id' => 4,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            // Booking für Vue Termin 2
            [
                'status' => 'accepted',
                'comment' => null,
                'appointment_id' => 2, // Vue Termin 2
                'tutor_id' => 1,
                'student_id' => 4,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            // Booking für JavaScript Termin 1
            [
                'status' => 'finished',
                'comment' => null,
                'appointment_id' => 3, // JavaScript Termin 1
                'tutor_id' => 1,
                'student_id' => 4,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
        ]);

    }
}
