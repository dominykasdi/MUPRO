<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Step;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CuratorStepController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('curator');
    }

    // Kurso dalių kūrimo lango nukreipėjas

    public function create(Course $course)
    {
        $existingSteps = $course->steps()->orderBy('order')->get();
        return Inertia::render('Courses/Steps/Curator/Create', [
            'course' => $course,
            'existingSteps' => $existingSteps,
        ]);
    }

    // Kurso dalių patalpinimo į duomenų bazę kontroleris
    // Taip pat nukreipia į kurso peržiūrą

    public function store(Request $request, Course $course)
    {
        $request->validate([
            'steps' => 'required|array',
            'steps.*.title' => 'required|string|max:100',
            'steps.*.description' => 'required|string|max:1000',
            'steps.*.order' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($course) {
                    if ($course->steps->contains('order', $value)) {
                        $fail('Dalies eiliškumas būtinas arba toks eiliškumas jau egzistuoja.');
                    }
                },
            ],
            'steps.*.youtube_video_id' => 'required|nullable|string',
        ]);

        foreach ($request->input('steps') as $stepData) {
            $step = new Step($stepData);
            $step->course_id = $course->id;
            $step->save();
        }

        return redirect()->route('courses.show', $course->id)->with('success', 'Dalis sukurta sėkmingai.');
    }

    public function update(Request $request, Step $step, Course $course)
    {
        // Tikrina, ar sukurta dalis tikrai priklauso konkrečiam kuratoriui
        if ($step->course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Šiam veiksmui atlikti neturite leidimo.'], 403);
        }

        // Validuoja įvestus duomenis
        $request->validate([
            'title' => 'required|max:100',
            'description' => 'required|max:1000',
            // 'order' => 'required|integer',
            'youtube_video_id' => 'required|string',
        ]);

        // Atnaujina dalį
        $step->update($request->only('title', 'description', 'youtube_video_id'));
    }

    // Visų dalių surinkimas, kuris naudojamas kuratoriaus peržiūros puslapyje

    public function getSteps(Course $course)
    {
        $steps = $course->steps;
        return response()->json($steps);
    }
}
