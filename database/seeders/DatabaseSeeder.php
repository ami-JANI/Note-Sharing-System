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
            '1-1' => ['CSE101' => 'Structured Programming', 'MATH101' => 'Calculus I'],
            '1-2' => ['CSE201' => 'Data Structures', 'CSE202' => 'Digital Logic Design'],
            '2-1' => ['CSE301' => 'Algorithms', 'CSE302' => 'Database Systems'],
            '2-2' => [],
            '3-1' => [],
            '3-2' => [],
            '4-1' => [],
            '4-2' => [],
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
