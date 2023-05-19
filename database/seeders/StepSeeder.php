<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Step;
use App\Models\Course;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = Course::all();

        $stepsData = [
            [
                'title' => 'Introduction to Music Production',
                'description' => 'An overview of the music production process and essential concepts.',
                'order' => 1,
                'youtube_video_id' => 'example_video_id_1',
            ],
            [
                'title' => 'DAW Basics',
                'description' => 'Learn the basics of using a Digital Audio Workstation (DAW) for music production.',
                'order' => 2,
                'youtube_video_id' => 'example_video_id_2',
            ],
            
        ];

        foreach ($courses as $course) {
            foreach ($stepsData as $stepData) {
                Step::create(array_merge($stepData, ['course_id' => $course->id]));
            }
        }
    }
}
