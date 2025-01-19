<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with('users')->orderBy('datum_zeit', 'desc')->get();
        return view('admin.member.training', compact('trainings'));
    }

    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'grund' => 'required|string|max:255',
            'info' => 'nullable|string',
            'leitung' => 'required|string|max:255',
            'datum_zeit' => 'required|date'
        ]);

        Training::create($validated);

        return redirect()->route('training.index')
            ->with('success', 'Training wurde erfolgreich erstellt');
    } catch (\Exception $e) {
        return redirect()->route('training.index')
            ->with('error', 'Fehler beim Erstellen des Trainings');
    }
}

public function register($id)
{
    try {
        $training = Training::findOrFail($id);
        $training->users()->attach(auth()->id());
        return redirect()->back()->with('success', 'Erfolgreich zum Training angemeldet');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Fehler bei der Anmeldung');
    }
}

public function unregister($id)
{
    try {
        $training = Training::findOrFail($id);
        $training->users()->detach(auth()->id());
        return redirect()->back()->with('success', 'Erfolgreich vom Training abgemeldet');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Fehler bei der Abmeldung');
    }
}
}