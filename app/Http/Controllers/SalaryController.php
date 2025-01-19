<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Salary;
use App\Models\SalaryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FinancialEntry;

class SalaryController extends Controller
{
    public function index()
    {
        $employees = User::with(['salary', 'salaryHistory.createdBy'])
                    ->orderBy('name')
                    ->get();

        return view('admin.finance.salaries', compact('employees'));
    }


    public function storeEntry(Request $request)
{
    $validated = $request->validate([
        'employee_id' => 'required|exists:users,id',
        'type' => 'required|in:salary,share,tips',
        'amount' => 'required|numeric|min:0',
        'note' => 'nullable|string'
    ]);

    // Get or create salary record
    $salary = Salary::firstOrCreate(
        ['employee_id' => $validated['employee_id']],
        ['salary' => 0, 'share' => 0, 'tips' => 0]
    );

    // Add to existing amount
    $currentAmount = $salary->{$validated['type']};
    $newAmount = $currentAmount + $validated['amount'];
    
    $salary->update([
        $validated['type'] => $newAmount
    ]);

    // Create history entry
    SalaryHistory::create([
        'employee_id' => $validated['employee_id'],
        'type' => $validated['type'],
        'amount' => $validated['amount'],
        'note' => $validated['note'],
        'created_by' => Auth::id()
    ]);

    return redirect()->back()->with('success', 'Eintrag wurde erstellt');
}

public function getHistory($id)
{
    $typeNames = [
        'salary' => 'Gehalt',
        'share' => 'Anteil',
        'tips' => 'Trinkgeld',
        'payout' => 'Auszahlung'
    ];

    $history = SalaryHistory::where('employee_id', $id)
        ->with('createdBy')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($entry) use ($typeNames) {
            return [
                'type' => $typeNames[$entry->type] ?? $entry->type,
                'amount' => number_format($entry->amount, 2, ',', '.') . ' €',
                'note' => $entry->note,
                'created_at' => $entry->created_at->format('d.m.Y H:i'),
                'created_by' => $entry->createdBy->name
            ];
        });

    return response()->json($history);
}
public function processPayout(Request $request)
{
    $validated = $request->validate([
        'employee_id' => 'required|exists:users,id',
        'salary' => 'required|numeric|min:0',
        'share' => 'required|numeric|min:0',
        'tips' => 'required|numeric|min:0'
    ]);

    $employee = User::find($validated['employee_id']);
    $salary = Salary::where('employee_id', $validated['employee_id'])->first();

    if ($salary) {
        // Validate payout amounts don't exceed balances
        if ($validated['salary'] > $salary->salary || 
            $validated['share'] > $salary->share || 
            $validated['tips'] > $salary->tips) {
            return redirect()->back()->with('error', 'Auszahlungsbetrag übersteigt Guthaben');
        }

        // Create history entry for payout
        SalaryHistory::create([
            'employee_id' => $validated['employee_id'],
            'type' => 'payout',
            'amount' => $validated['salary'] + $validated['share'] + $validated['tips'],
            'note' => 'Auszahlung',
            'created_by' => Auth::id()
        ]);

        // Create financial entries and update remaining amounts
        if ($validated['salary'] > 0) {
            FinancialEntry::create([
                'type' => 'Ausgabe',
                'category' => 'Gehalt',
                'note' => "Gehalt Auszahlung {$employee->name} ({$validated['salary']}€)",
                'amount' => $validated['salary'],
                'user_id' => Auth::id()
            ]);
            $salary->salary -= $validated['salary'];
        }

        if ($validated['share'] > 0) {
            FinancialEntry::create([
                'type' => 'Ausgabe',
                'category' => 'Anteil',
                'note' => "Anteil Auszahlung {$employee->name} ({$validated['share']}€)",
                'amount' => $validated['share'],
                'user_id' => Auth::id()
            ]);
            $salary->share -= $validated['share'];
        }

        if ($validated['tips'] > 0) {
            FinancialEntry::create([
                'type' => 'Ausgabe',
                'category' => 'Trinkgeld',
                'note' => "Trinkgeld Auszahlung {$employee->name} ({$validated['tips']}€)",
                'amount' => $validated['tips'],
                'user_id' => Auth::id()
            ]);
            $salary->tips -= $validated['tips'];
        }

        $salary->save();

        return redirect()->back()->with('success', 'Auszahlung wurde durchgeführt');
    }

    return redirect()->back()->with('error', 'Mitarbeiter nicht gefunden');
}
}