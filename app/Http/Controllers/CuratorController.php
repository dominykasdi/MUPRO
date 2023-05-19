<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CuratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Vartotojo autentifikavimas
        $this->middleware('curator'); // Tik kuratoriaus rolė gali pasiekti šį valdiklį
    }

    // Visų sukurtų kuratoriaus kursų puslapio valdymas
    public function index()
    {
        $user = auth()->user();
        $courses = Course::with('tags')->where('user_id', $user->id)->get();
        $tags = Tag::all();

        return Inertia::render('Courses/Curator/Index', ['courses' => $courses, 'tags' => $tags]);
    }

    // Nukreipimas į kurso sukūrimo puslapį
    public function create()
    {
        $tags = Tag::all();
        return Inertia::render('Courses/Curator/Create', [
            'tags' => $tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            }),
        ]);
    }

    // Sukurto arba jau egzistuojančio kurso puslapis, rodomas kuratoriui
    public function show($id)
    {
        $course = Course::with('tags')->withCount('enrolledLearners')->findOrFail($id);
        $steps = $course->steps()->orderBy('order', 'asc')->get();

        return Inertia::render('Courses/Curator/Show', [
            'course' => $course,
            'steps' => $steps,
            'inProgressCount' => $course->enrolled_learners_count,
        ]);
    }

    // Kurso talpinimas į duomenų bazę
    // Nukreipimas į kurso dalių kūrimo puslapį

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:10|max:100',
            'description' => 'required|max:1000',
            'selectedTags' => ['required', 'array'],
            'selectedTags.*' => ['integer', 'exists:tags,id'],
        ]);

        $course = new Course($request->only('title', 'description'));
        $course->user_id = auth()->user()->id;
        $course->save();

        // Pasirinktos žymos pridedamos prie kurso
        $course->tags()->attach($request->input('selectedTags'));

        return redirect()->route('courses.steps.create', $course->id);
    }

    // Kurso redagavimo perdavimas į duomenų bazę
    // Nukreipia į visų kursų puslapį

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|min:10|max:100',
            'description' => 'required|max:1000',
        ]);

        $course->update($request->only('title', 'description'));

        return redirect()->route('courses.show', $course->id);
    }

    // Kurso ištrynimas, kartu ištrinant ir visas jo dalis
    public function destroy(Course $course)
    {
        // Prieš ištrinant kursą, ištrinamos ir visos susijusios dalys
        $course->steps()->delete();

        $course->delete();
        return Redirect::to('/kuratorius/kursai');
    }

    // Įsiregistravimų puslapio rodymo funkcija
    public function enrollmentIndex()
    {
        $user = auth()->user();

        // Gaunami kursai su asocijuojamais įsiregistravimais ir mokiniais
        $courses = Course::with(['enrollments.user', 'enrollments.course'])->where('user_id', $user->id)->get();

        $enrollments = collect();

        foreach ($courses as $course) {
            foreach ($course->enrollments as $enrollment) {
                $enrollments->push($enrollment);
            }
        }

        $enrollments->each->append('status');

        return Inertia::render('Enrollments/Curator/Index', compact('enrollments'));
    }
}