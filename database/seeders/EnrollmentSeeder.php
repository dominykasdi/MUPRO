<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        // Sukuriami keli mokiniai
        $learners = User::factory()->count(4)->create();

        // Sukuriami keli kursai
        $courses = Course::factory()->count(4)->create();

        // Mokiniai įregistruojami į sukurtus kursus
        foreach ($learners as $learner) {
            foreach ($courses as $course) {
                Enrollment::create([
                    'user_id' => $learner->id,
                    'course_id' => $course->id,
                ]);
            }
        }
    }
}
