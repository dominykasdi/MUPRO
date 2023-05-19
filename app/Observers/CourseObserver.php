<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\Step;

class CourseObserver
{
    public function deleted(Course $course)
    {
        // Delete all steps associated with the course
        $course->steps()->delete();
    }
}

