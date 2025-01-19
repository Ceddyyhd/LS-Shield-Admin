<?php

namespace App\Http\Controllers;

use App\Models\Deckel;
use App\Models\FinancialEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeckelController extends Controller
{
    public function index()
    {
        $locations = Deckel::select('location', DB::raw('SUM(betrag) as total_betrag'))
                    ->groupBy('location')
                    ->get();

        return view('admin.verwaltung.deckel', compact('locations'));
    }
    public function deleteLocation(Request $request)
    {
        try {
            DB::beginTransaction();

            $deckels = Deckel::where('location', $request->location)->get();
            $totalAmount = $deckels->sum('betrag');

            // Create financial entry
            FinancialEntry::create([
                'type' => 'Ausgabe',
                'category' => 'Tanken',
                'note' => "Deckel bezahlt für {$request->location}",
                'amount' => $totalAmount,
                'user_id' => Auth::id()
            ]);

            // Delete deckels
            Deckel::where('location', $request->location)->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Deckel erfolgreich gelöscht und in Finanzen eingetragen']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}