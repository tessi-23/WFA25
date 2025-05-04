<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lessons')->insert([
            [
                'title' => 'Vue',
                'description' => 'Grundlagen der Webentwicklung mit Vue',
                'tutor_id' => 1,
                'category_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'title' => 'JavaScript',
                'description' => 'Grundlagen von JavaScript',
                'tutor_id' => 1,
                'category_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'title' => 'C#',
                'description' => 'Grundlagen der Programmierung mit C#',
                'tutor_id' => 3,
                'category_id' => 3,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'title' => 'Blender',
                'description' => 'Grundlagen der 3D-Animation mit Blender',
                'tutor_id' => 2,
                'category_id' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ]);
    }
}
