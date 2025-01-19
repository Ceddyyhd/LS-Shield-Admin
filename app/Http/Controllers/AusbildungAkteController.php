<?php

namespace App\Http\Controllers;

use App\Models\Ausbildung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AusbildungAkteController extends Controller
{
    public function show($id)
    {
        $ausbildung = Ausbildung::findOrFail($id);
        return view('admin.ausbildung.ausbildung_akte', compact('ausbildung'));
    }

    public function updateContent(Request $request, $id)
    {
        $ausbildung = Ausbildung::findOrFail($id);
        $ausbildung->update(['content' => $request->content]);
        
        return redirect()->back()->with('success', 'Leitfaden wurde aktualisiert');
    }
    public function uploadImage(Request $request)
{
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('ausbildung_images', 'public');
        return response()->json(['url' => asset('storage/' . $path)]);
    }
    return response()->json(['error' => 'No image uploaded'], 400);
}
}