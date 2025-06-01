<?php

namespace Database\Seeders;

use App\Models\Lesson;
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
//        // Lesson 1: Vue
//        $lesson1 = new Lesson;
//        $lesson1->title = 'Vue';
//        $lesson1->description = 'Grundlagen der Webentwicklung mit Vue';
//        $lesson1->tutor_id = 1;
//        $lesson1->category_id = 1;
//        $lesson1->created_at = date("Y-m-d H:i:s");
//        $lesson1->updated_at = date("Y-m-d H:i:s");
//        $lesson1->save();
//
//        // Lesson 2: JavaScript
//        $lesson2 = new Lesson;
//        $lesson2->title = 'JavaScript';
//        $lesson2->description = 'Grundlagen von JavaScript';
//        $lesson2->tutor_id = 1;
//        $lesson2->category_id = 1;
//        $lesson2->created_at = date("Y-m-d H:i:s");
//        $lesson2->updated_at = date("Y-m-d H:i:s");
//        $lesson2->save();
//
//        // Lesson 3: C#
//        $lesson3 = new Lesson;
//        $lesson3->title = 'C#';
//        $lesson3->description = 'Grundlagen der Programmierung mit C#';
//        $lesson3->tutor_id = 3;
//        $lesson3->category_id = 3;
//        $lesson3->created_at = date("Y-m-d H:i:s");
//        $lesson3->updated_at = date("Y-m-d H:i:s");
//        $lesson3->save();
//
//        // Lesson 4: Blender
//        $lesson4 = new Lesson;
//        $lesson4->title = 'Blender';
//        $lesson4->description = 'Grundlagen der 3D-Animation mit Blender';
//        $lesson4->tutor_id = 2;
//        $lesson4->category_id = 2;
//        $lesson4->created_at = date("Y-m-d H:i:s");
//        $lesson4->updated_at = date("Y-m-d H:i:s");
//        $lesson4->save();
//
        // Lesson 5: E-Learning
        $lesson5 = new Lesson;
        $lesson5->title = 'E-Leraning';
        $lesson5->description = 'Grundlagen im E-Learning';
        $lesson5->tutor_id = 1;
        $lesson5->category_id = 1;
        $lesson5->created_at = date("Y-m-d H:i:s");
        $lesson5->updated_at = date("Y-m-d H:i:s");
        $lesson5->save();
    }
}
