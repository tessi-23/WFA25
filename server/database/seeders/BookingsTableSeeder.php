<?php

namespace Database\Seeders;

use App\Models\Booking;
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
        // Booking für Vue Termin 1
        //$booking1 = new Booking;
        //$booking1->status = 'rejected';
        //$booking1->comment = 'Geht auch der 10.Juni?';
        //$booking1->appointment_id = 1; // Vue Einführung
        //$booking1->tutor_id = 1;
        //$booking1->student_id = 4;
        //$booking1->created_at = date("Y-m-d H:i:s");
        //$booking1->updated_at = date("Y-m-d H:i:s");
        //$booking1->save();

        // Booking für Vue Termin 2
        $booking2 = new Booking;
        $booking2->status = 'pending';
        $booking2->comment = null;
        $booking2->appointment_id = 2; // Vue Termin 2
        $booking2->tutor_id = 1;
        $booking2->student_id = 4;
        $booking2->created_at = date("Y-m-d H:i:s");
        $booking2->updated_at = date("Y-m-d H:i:s");
        $booking2->save();

        // Booking für JavaScript Termin 1
        $booking3 = new Booking;
        $booking3->status = 'finished';
        $booking3->comment = null;
        $booking3->appointment_id = 3; // JavaScript Termin 1
        $booking3->tutor_id = 1;
        $booking3->student_id = 4;
        $booking3->created_at = date("Y-m-d H:i:s");
        $booking3->updated_at = date("Y-m-d H:i:s");
        $booking3->save();
    }
}
