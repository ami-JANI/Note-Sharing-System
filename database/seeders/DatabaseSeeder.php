<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@note-sharing.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $semesters = [
            'Semester 1' => ['CSE101' => 'Structured Programming', 'MATH101' => 'Calculus I'],
            'Semester 2' => ['CSE201' => 'Data Structures', 'CSE202' => 'Digital Logic Design'],
            'Semester 3' => ['CSE301' => 'Algorithms', 'CSE302' => 'Database Systems'],
        ];

        $order = 0;
        foreach ($semesters as $semesterName => $subjects) {
            $semester = Semester::create([
                'name' => $semesterName,
                'order' => $order++,
            ]);

            foreach ($subjects as $code => $name) {
                Subject::create([
                    'semester_id' => $semester->id,
                    'code' => $code,
                    'name' => $name,
                ]);
            }
        }
    }
}
