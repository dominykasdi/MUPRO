<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StepController extends Controller
{
    // Pasiekiama tik kuratoriui

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('curator');
    }

    // Kurso dalies ištrinimas
    // Kurso dalių išdėstymas atsinaujina pagal eiliškumą

    public function destroy(Request $request, Step $step)
    {
        // Tikrinama, ar kurso dalis priklauso kuratoriui
        if ($step->course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Šiam veiksmui atlikti neturite leidimo.'], 403);
        }

        $courseId = $step->course_id;
        $deletedStepOrder = $step->order;
        $step->delete();

        $this->updateOrderNumbersAfterDeletion($courseId, $deletedStepOrder);

        return response()->json(['message' => 'Dalis ištrinta sėkmingai.'], 200);
    }

    // Kurso dalių eiliškumo atnaujinimas po ištrynimo
    // Naudojamas ištrinimo funkcijoje

    public function updateOrderNumbersAfterDeletion($courseId, $deletedStepOrder)
    {
        // Suranda visas dalis, kurios yra aukščiau ištrintos eiliškumo prasme
        $stepsToUpdate = Step::where('course_id', $courseId)
            ->where('order', '>', $deletedStepOrder)
            ->get();
    
        // Log::info('stepsToUpdate', ['stepsToUpdate' => $stepsToUpdate]);
    
        // Eiliškumo skaičius sumažinamas

        foreach ($stepsToUpdate as $step) {
            $step->order--;
            $step->save();
        }
    }
}
