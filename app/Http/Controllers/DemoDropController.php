<?php

namespace App\Http\Controllers;

use App\Models\DemoDrop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DemoDropController extends Controller
{
    // Rolių tikrinimas ir atitinkamų duomenų užkrovimas

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('curator')) {
            $demoDrops = DemoDrop::with('learner')->get();
        } else {
            // Mokiniams rodomi tik jų perklausai išsiųsti demo
            $demoDrops = DemoDrop::with('learner')->where('learner_id', $user->id)->get();
        }

        return response()->json(['data' => $demoDrops]);
    }

    // Demo perklausos mokinio sąsajai

    public function fetchLearnerDemos()
    {
        $user = auth()->user();
        
        $demoDrops = DemoDrop::with('learner')
            ->select('id', 'learner_id', 'curator_id', 'file_path', 'demo_title', 'note', 'feedback', 'is_checked', 'created_at', 'updated_at')
            ->where('learner_id', $user->id)
            ->get();

        $demoDrops->each(function ($demoDrop) {
            $curator = User::find($demoDrop->curator_id);
            $demoDrop->curator_name = $curator ? $curator->name : null;
        });

        return response()->json(['data' => $demoDrops]);
    }

    // Mokiniams parodomas kuratorių sąrašas siunčiant demo perklausai

    public function fetchCurators()
    {
        $curators = User::role('curator')->get();

        return response()->json(['data' => $curators]);
    }

    // Demo perklausos kuratoriaus sąsajai

    public function fetchCuratorDemos($status = null)
    {
        $user = auth()->user();

        if ($user->hasRole('curator')) {
            $query = DemoDrop::with(['learner' => function ($query) {
                $query->select('id', 'name');
            }])
                ->where('curator_id', $user->id)
                ->select('id', 'learner_id', 'curator_id', 'file_path', 'demo_title', 'note', 'feedback', 'is_checked', 'created_at', 'updated_at');

            if ($status === 'checked') {
                $query->where('is_checked', true);
            } elseif ($status === 'unchecked') {
                $query->where('is_checked', false);
            }

            $demoDrops = $query->get();
        } else {
            abort(403);
        }

        return response()->json(['data' => $demoDrops]);
    }

    // Mokinio demo įrašo pateikimas perklausai ir talpinimas

    public function store(Request $request)
    {
        $request->validate([
            'demo_file' => 'required|mimes:mp3,wav|max:51200',
            'demo_title' => 'required|string|max:100',
            'note' => 'nullable|string|max:1000',
            'curator_id' => 'required|exists:users,id',
        ]);

        $user = auth()->user();

        $file = $request->file('demo_file');
        $path = 'storage/' . $file->store('demo_drops', 'public');

        $demoDrop = new DemoDrop([
            'learner_id' => $user->id,
            'curator_id' => $request->input('curator_id'),
            'file_path' => $path,
            'demo_title' => $request->input('demo_title'),
            'note' => $request->input('note'),
        ]);

        $demoDrop->save();

        return redirect()->back()->with('success', 'Demo sėkmingai įkeltas ir patalpintas.');
    }

    // Kuratorius užpildo papildomus laukus pateikdamas atsiliepimą

    public function update(Request $request, DemoDrop $demoDrop)
    {
        $request->validate([
            'feedback' => 'required|string|max:1000',
            'is_checked' => 'required|boolean',
        ]);

        $user = auth()->user();

        if (!$user->hasRole('curator')) {
            abort(403);
        }

        $demoDrop->update([
            'curator_id' => $user->id,
            'feedback' => $request->input('feedback'),
            'is_checked' => $request->input('is_checked'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Atsiliepimas pateiktas ir demo pažymėtas kaip perklausytas.',
        ]);
    }

    // Demo perklausos ištrynimas (sukurta, bet nenaudojama)

    public function destroy(DemoDrop $demoDrop)
    {
        Storage::disk('public')->delete($demoDrop->file_path);
        $demoDrop->delete();

        return redirect()->back()->with('success', 'Demo ištrintas sėkmingai.');
    }
}
