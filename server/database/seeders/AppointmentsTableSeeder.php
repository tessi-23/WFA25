<?php

namespace Database\Seeders;

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
        DB::table('appointments')->insert([
            // Vue Termin 1
            [
                'title' => 'Vue Einführung',
                'date' => '2025-05-10',
                'start' => '10:00:00',
                'end' => '12:00:00',
                'status' => 'available',
                'lesson_id' => 1, // Vue
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            // Vue Termin 2
            [
                'title' => 'Vue Fortgeschrittene Themen',
                'date' => '2025-05-17',
                'start' => '14:00:00',
                'end' => '16:00:00',
                'status' => 'booked',
                'lesson_id' => 1, // Vue
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            // JavaScript Termin 1
            [
                'title' => 'JavaScript Einführung',
                'date' => '2025-05-01',
                'start' => '09:00:00',
                'end' => '11:00:00',
                'status' => 'done',
                'lesson_id' => 2, // JavaScript
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            // JavaScript Termin 2
            [
                'title' => 'JavaScript Fortgeschrittene Themen',
                'date' => '2025-05-19',
                'start' => '15:00:00',
                'end' => '17:00:00',
                'status' => 'available',
                'lesson_id' => 2, // JavaScript
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            // C# Termin 1
            [
                'title' => 'C# Grundlagen',
                'date' => '2025-05-15',
                'start' => '13:00:00',
                'end' => '14:00:00',
                'status' => 'available',
                'lesson_id' => 3, // C#
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            // Blender Termin 1
            [
                'title' => 'Blender Einführung',
                'date' => '2025-05-20',
                'start' => '11:00:00',
                'end' => '13:00:00',
                'status' => 'available',
                'lesson_id' => 4, // Blender
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
        ]);

    }
}
