<?php

namespace App\Http\Controllers;

use App\Models\FinancialEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index()
    {
        $entries = FinancialEntry::with('user')->latest()->get();
        
        $balance = FinancialEntry::where('type', 'Einnahme')->sum('amount') 
                  - FinancialEntry::where('type', 'Ausgabe')->sum('amount');
        
        $income = FinancialEntry::where('type', 'Einnahme')->sum('amount');
        $expenses = FinancialEntry::where('type', 'Ausgabe')->sum('amount');

        return view('admin.finance.finance', compact(
            'entries',
            'balance',
            'income',
            'expenses'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:Einnahme,Ausgabe',
            'category' => 'required|string',
            'note' => 'nullable|string',
            'amount' => 'required|numeric|min:0'
        ]);
    
        FinancialEntry::create([
            'type' => $validated['type'],
            'category' => $validated['category'],
            'note' => $validated['note'],
            'amount' => $validated['amount'],
            'user_id' => Auth::id()
        ]);
    
        return redirect()->route('admin.finance.index')
            ->with('success', 'Eintrag wurde erstellt');
    }
}