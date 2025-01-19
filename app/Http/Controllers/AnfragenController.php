<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnfragenController extends Controller
{
    public function index()
    {
        // Anfragen aus der Datenbank abrufen
        $anfragen = DB::table('anfragen')
            ->select('id', 'vorname_nachname', 'telefonnummer', 'anfrage', 'datum_uhrzeit', 'erstellt_von', 'status')
            ->orderBy('datum_uhrzeit', 'desc')
            ->get();

        return view('admin.planung.anfragen', compact('anfragen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nummer' => 'required|string|max:255',
            'anfrage' => 'required|string',
        ]);

        DB::table('anfragen')->insert([
            'vorname_nachname' => $request->name,
            'telefonnummer' => $request->nummer,
            'anfrage' => $request->anfrage,
            'datum_uhrzeit' => now(),
            'erstellt_von' => Auth::user()->name,
            'status' => 'Eingetroffen',
        ]);

        return redirect()->route('anfragen.index')->with('success', 'Anfrage erfolgreich erstellt!');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string',
        ]);

        $anfrage = DB::table('anfragen')->where('id', $request->id)->first();

        if ($request->status === 'in Planung') {
            DB::table('eventplanung')->insert([
                'vorname_nachname' => $anfrage->vorname_nachname,
                'telefonnummer' => $anfrage->telefonnummer,
                'anfrage' => $anfrage->anfrage,
                'datum_uhrzeit' => $anfrage->datum_uhrzeit,
                'status' => 'in Planung',
            ]);

            // Löschen des Eintrags aus der anfragen-Tabelle
            DB::table('anfragen')->where('id', $request->id)->delete();
        } else {
            DB::table('anfragen')
                ->where('id', $request->id)
                ->update(['status' => $request->status]);
        }

        return redirect()->route('anfragen.index')->with('success', 'Status erfolgreich aktualisiert!');
    }
}