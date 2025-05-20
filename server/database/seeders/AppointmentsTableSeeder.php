<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vue Termin 1
        $appointment1 = new Appointment;
        $appointment1->title = 'Vue Einführung';
        $appointment1->date = '2025-06-10';
        $appointment1->start = '10:00:00';
        $appointment1->end = '12:00:00';
        $appointment1->status = 'available';
        $appointment1->price = 10;
        $appointment1->lesson_id = 1; // Vue
        $appointment1->created_at = date("Y-m-d H:i:s");
        $appointment1->updated_at = date("Y-m-d H:i:s");
        $appointment1->save();

        // Vue Termin 2
        $appointment2 = new Appointment;
        $appointment2->title = 'Vue Fortgeschrittene Themen';
        $appointment2->date = '2025-06-17';
        $appointment2->start = '14:00:00';
        $appointment2->end = '16:00:00';
        $appointment2->status = 'available';
        $appointment2->price = 11;
        $appointment2->lesson_id = 1; // Vue
        $appointment2->created_at = date("Y-m-d H:i:s");
        $appointment2->updated_at = date("Y-m-d H:i:s");
        $appointment2->save();

        // JavaScript Termin 1
        $appointment3 = new Appointment;
        $appointment3->title = 'JavaScript Einführung';
        $appointment3->date = '2025-05-01';
        $appointment3->start = '09:00:00';
        $appointment3->end = '11:00:00';
        $appointment3->status = 'available';
        $appointment3->price = 10;
        $appointment3->lesson_id = 2; // JavaScript
        $appointment3->created_at = date("Y-m-d H:i:s");
        $appointment3->updated_at = date("Y-m-d H:i:s");
        $appointment3->save();

        // JavaScript Termin 2
        $appointment4 = new Appointment;
        $appointment4->title = 'JavaScript Fortgeschrittene Themen';
        $appointment4->date = '2025-06-19';
        $appointment4->start = '15:00:00';
        $appointment4->end = '17:00:00';
        $appointment4->status = 'available';
        $appointment4->price = 20;
        $appointment4->lesson_id = 2; // JavaScript
        $appointment4->created_at = date("Y-m-d H:i:s");
        $appointment4->updated_at = date("Y-m-d H:i:s");
        $appointment4->save();

        // C# Termin 1
        $appointment5 = new Appointment;
        $appointment5->title = 'C# Grundlagen';
        $appointment5->date = '2025-06-15';
        $appointment5->start = '13:00:00';
        $appointment5->end = '14:00:00';
        $appointment5->status = 'available';
        $appointment5->price = 20;
        $appointment5->lesson_id = 3; // C#
        $appointment5->created_at = date("Y-m-d H:i:s");
        $appointment5->updated_at = date("Y-m-d H:i:s");
        $appointment5->save();

        // Blender Termin 1
        $appointment6 = new Appointment;
        $appointment6->title = 'Blender Einführung';
        $appointment6->date = '2025-06-20';
        $appointment6->start = '11:00:00';
        $appointment6->end = '13:00:00';
        $appointment6->status = 'available';
        $appointment6->price = 15;
        $appointment6->lesson_id = 4; // Blender
        $appointment6->created_at = date("Y-m-d H:i:s");
        $appointment6->updated_at = date("Y-m-d H:i:s");
        $appointment6->save();

        // E-Learning Termin 1
        $appointment7 = new Appointment;
        $appointment7->title = 'E-Learning Basics';
        $appointment7->date = '2025-04-01';
        $appointment7->start = '11:00:00';
        $appointment7->end = '18:00:00';
        $appointment7->status = 'booked';
        $appointment7->price = 35;
        $appointment7->lesson_id = 5; // E Learning
        $appointment7->created_at = date("Y-m-d H:i:s");
        $appointment7->updated_at = date("Y-m-d H:i:s");
        $appointment7->save();

    }
}
