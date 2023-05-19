<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Course $course)
    {
        $reviews = $course->reviews()->with('user')->get();

        return response()->json($reviews);
    }

    // Kurso atsiliepimų talpinimas

    public function store(Request $request, Course $course)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review = new Review([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $review->user()->associate($request->user());
        $course->reviews()->save($review);

        return redirect()->route('courses.view', $course->id)->with('success', 'Atsiliepimas sėkmingai pateiktas!');
    }
}
