<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LearnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Vartotojo autentifikavimas
        $this->middleware('learner'); // Tik mokinio rolė gali pasiekti šį valdiklį
    }

    // Su įsiregistravimu susijusi logika

    // Visų kursų, į kuriuos yra įsiregistruota, puslapio logika

    public function index()
    {
        $user = auth()->user();
        $enrollments = $user->enrollments()->with('course')->get();
        $enrollments->each->append(['status']);

        return Inertia::render('Enrollments/Learner/Index', compact('enrollments'));
    }

    // Įsiregistruoto kurso palikimas

    public function unenroll(Enrollment $enrollment)
    {
        $user = auth()->user();

        // Tikrinama, ar mokiniui priklauso kursas prieš jį paliekant
        if ($user->id !== $enrollment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $enrollment->delete();

        return redirect()->route('enrollments.learner.index')->with('success', 'Sėkmingai išsiregistruota iš kurso.');
    }

    // Įsiregistruoto kurso puslapis

    public function enrolledCourse(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();

        // Tikrinama, ar įsiregistravimas priklauso mokiniui
        if ($enrollment->user_id != $user->id) {
            abort(403, 'Neleistinas veiksmas.');
        }

        $course = Course::with(['steps', 'curator', 'tags'])
            ->whereHas('enrollments', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->findOrFail($enrollment->course_id);

        $course->enrollment_id = $enrollment->id;
        $course->completed = $enrollment->completed;

        $steps = $course->steps()->orderBy('order', 'asc')->get();

        return Inertia::render('Enrollments/Learner/EnrolledCourse', [
            'enrollment' => $enrollment,
            'course' => $course,
            'steps' => $steps,
        ]);
    }

    // Įsiregistravimo į kursą logika

    public function enroll(Course $course)
    {
        $user = Auth::user();
        $enrollment = Enrollment::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        return Redirect::route('enrollments.learner.index');
    }

    // Pasiekiami kursai mokiniui įsiregistruoti

    public function availableCourses()
    {
        $user = auth()->user();
        $courses = Course::with('tags')->get();
        $tags = Tag::all();
        $enrolledCourses = auth()->user()->enrollments;

        return Inertia::render('Courses/Learner/AvailableCourses', [
            'courses' => $courses,
            'enrolledCourses' => $enrolledCourses,
            'tags' => $tags,
        ]);
    }

    // Kurso apžvalga prieš įsiregistruojant

    public function courseView(Course $course)
    {
        $course->load(['steps', 'curator', 'tags']);
        $enrolledCourses = auth()->user()->enrollments;
        
        return Inertia::render('Courses/Learner/ShowCourse', 
        compact('course', 'enrolledCourses'));
    }

    // Pažymėti įsiregistruotą kursą baigtu

    public function markCourseAsComplete(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();

        // Tikrinama, ar įsiregistravimas priklauso mokiniui
        if ($enrollment->user_id != $user->id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $enrollment->completed = true;
        $enrollment->save();

        return response()->json(['message' => 'Kursas pažymėtas kaip baigtas.']);
    }
}
