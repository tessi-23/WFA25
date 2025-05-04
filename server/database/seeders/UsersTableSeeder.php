<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutor1 = new User;
        $tutor1->firstname = 'Anna';
        $tutor1->lastname = 'Mayer';
        $tutor1->phone = '06601234567';
        $tutor1->age = 29;
        $tutor1->gender = 'female';
        $tutor1->qualification = 'BSc KWM';
        $tutor1->role = 'tutor';
        $tutor1->description = 'Ich unterrichte seit 5 Jahren KWM Fächer.';
        $tutor1->email = 'anna.mayer@example.com';
        $tutor1->password = bcrypt('secret');
        $tutor1->save();

        $tutor2 = new User;
        $tutor2->firstname = 'Lukas';
        $tutor2->lastname = 'Schmid';
        $tutor2->phone = '06771234567';
        $tutor2->age = 35;
        $tutor2->gender = 'male';
        $tutor2->qualification = 'BSc MTD';
        $tutor2->role = 'tutor';
        $tutor2->description = 'Ich helfe gerne bei 3D-Animationen.';
        $tutor2->email = 'lukas.schmid@example.com';
        $tutor2->password = bcrypt('secret');
        $tutor2->save();

        $tutor3 = new User;
        $tutor3->firstname = 'Miriam';
        $tutor3->lastname = 'Huber';
        $tutor3->phone = '06501234567';
        $tutor3->age = 27;
        $tutor3->gender = 'female';
        $tutor3->qualification = 'MSc Software Engineering';
        $tutor3->role = 'tutor';
        $tutor3->description = 'Ich helfe bei der Softwareentwicklung.';
        $tutor3->email = 'miriam.huber@example.com';
        $tutor3->password = bcrypt('secret');
        $tutor3->save();

//        $tutor4 = new User;
//        $tutor4->firstname = 'Sebastian';
//        $tutor4->lastname = 'Krenn';
//        $tutor4->phone = '06641234567';
//        $tutor4->age = 32;
//        $tutor4->gender = 'male';
//        $tutor4->qualification = 'BSc KWM';
//        $tutor4->role = 'tutor';
//        $tutor4->description = 'Biete Nachhilfe in JavaScript.';
//        $tutor4->email = 'sebastian.krenn@example.com';
//        $tutor4->password = bcrypt('secret');
//        $tutor4->save();


        $student = new User;
        $student->firstname = 'Jonas';
        $student->lastname = 'Fischer';
        $student->phone = '06801234567';
        $student->age = 21;
        $student->gender = 'male';
        $student->qualification = 'Student BA KWM';
        $student->role = 'student';
        $student->description = 'Suche Nachhilfe in Vue';
        $student->email = 'jonas.fischer@example.com';
        $student->password = bcrypt('secret');
        $student->save();

        $admin = new User;
        $admin->firstname = 'Admin';
        $admin->lastname = 'User';
        $admin->phone = '06001234567';
        $admin->age = 40;
        $admin->gender = 'other';
        $admin->qualification = 'System Admin';
        $admin->role = 'admin';
        $admin->description = 'Verwaltet die Plattform.';
        $admin->email = 'admin@example.com';
        $admin->password = bcrypt('secret');
        $admin->save();
    }
}
