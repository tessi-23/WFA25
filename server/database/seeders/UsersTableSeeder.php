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

        $student1 = new User;
        $student1->firstname = 'Jonas';
        $student1->lastname = 'Fischer';
        $student1->phone = '06801234567';
        $student1->age = 21;
        $student1->gender = 'male';
        $student1->qualification = 'Student BA KWM';
        $student1->role = 'student';
        $student1->description = 'Suche Nachhilfe in Vue';
        $student1->email = 'jonas.fischer@example.com';
        $student1->password = bcrypt('secret');
        $student1->save();

        $student2 = new User;
        $student2->firstname = 'Marlene';
        $student2->lastname = 'Sahne';
        $student2->phone = '0680124447';
        $student2->age = 30;
        $student2->gender = 'female';
        $student2->qualification = 'Student BA MTD';
        $student2->role = 'student';
        $student2->description = 'Suche Nachhilfe für MTD Fächer';
        $student2->email = 'marlene.sahne@example.com';
        $student2->password = bcrypt('secret');
        $student2->save();

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
