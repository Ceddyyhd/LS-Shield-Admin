<?php

namespace App\Http\Controllers;

use App\Models\Rabatt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RabattController extends Controller
{
    public function index()
    {
        $rabatte = Rabatt::orderBy('display_name')->get();
        return view('admin.verwaltung.rabatt', ['rabatte' => $rabatte]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'display_name' => 'required|string',
                'description' => 'required|string',
                'rabatt_percent' => 'required|integer|min:0|max:100'
            ]);

            $rabatt = Rabatt::create([
                ...$validated,
                'created_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Rabatt wurde erstellt');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Fehler beim Erstellen des Rabatts');
        }
    }

    public function edit($id)
{
    $rabatt = Rabatt::findOrFail($id);
    return response()->json($rabatt);
}

public function update(Request $request, $id)
{
    try {
        $rabatt = Rabatt::findOrFail($id);
        
        $validated = $request->validate([
            'display_name' => 'required|string',
            'description' => 'required|string',
            'rabatt_percent' => 'required|integer|min:0|max:100'
        ]);

        $rabatt->update($validated);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Fehler beim Aktualisieren'], 500);
    }
}
    public function destroy($id)
{
    try {
        $rabatt = Rabatt::findOrFail($id);
        $rabatt->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Fehler beim Löschen'], 500);
    }
}
}