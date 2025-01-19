<?php

namespace App\Http\Controllers;

use App\Models\Ausbildung;
use Illuminate\Http\Request;

class AusbildungController extends Controller
{
    public function index()
    {
        $ausbildungen = Ausbildung::orderBy('name')->get();
        return view('admin.ausbildung.ausbildung', compact('ausbildungen'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255'
    ]);

    Ausbildung::create($validated);
    
    if ($request->ajax()) {
        return response()->json(['success' => true]);
    }
    
    return redirect()->route('admin.ausbildungen.index')->with('success', 'Ausbildung wurde erstellt');
}

    public function edit($id)
    {
        $ausbildung = Ausbildung::findOrFail($id);
        return response()->json($ausbildung);
    }

    public function update(Request $request, $id)
    {
        $ausbildung = Ausbildung::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $ausbildung->update($validated);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $ausbildung = Ausbildung::findOrFail($id);
        $ausbildung->delete();
        return response()->json(['success' => true]);
    }
}