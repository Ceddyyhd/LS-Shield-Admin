<?php

namespace App\Http\Controllers;

use App\Models\Vacation;
use App\Models\VacationType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationController extends Controller
{
    public function index()
{
    $vacationTypes = VacationType::all();
    $myVacations = Vacation::where('user_id', auth()->id())
                    ->orderBy('start_date', 'desc')
                    ->get();

    // Get vacation events - filter out rejected
    $vacationEvents = Vacation::with('user', 'type')
                    ->whereIn('status', ['approved', 'pending'])
                    ->get()
                    ->map(function($vacation) {
                        return [
                            'title' => sprintf(
                                '%s - %s - %s',
                                $vacation->user->name,
                                $vacation->type->name,
                                ucfirst($vacation->status)
                            ),
                            'start' => $vacation->start_date,
                            'end' => $vacation->end_date,
                            'color' => $vacation->type->color,
                            'display' => 'block'
                        ];
                    });

    // Get event planning events
    $planningEvents = \App\Models\Event::all()
                    ->map(function($event) {
                        return [
                            'title' => $event->event,
                            'start' => $event->datum_uhrzeit,
                            'url' => route('eventplanung.show', $event->id),
                            'display' => 'list-item',
                            'color' => '#007bff'
                        ];
                    });

    $events = $vacationEvents->concat($planningEvents);

    return view('admin.member.vacation', compact('vacationTypes', 'myVacations', 'events'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vacation_type_id' => 'required|exists:vacation_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string'
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $durationInDays = $startDate->diffInDays($endDate) + 1;

        $vacation = Vacation::create([
            'user_id' => auth()->id(),
            'vacation_type_id' => $validated['vacation_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => $durationInDays <= 5 ? 'approved' : 'pending'
        ]);

        $message = $durationInDays <= 5 
            ? 'Urlaubsantrag wurde automatisch genehmigt (≤ 5 Tage)'
            : 'Urlaubsantrag wurde erfolgreich eingereicht';

        return redirect()->route('vacation.index')
            ->with('success', $message);
    }

    public function adminIndex()
{
    $users = User::orderBy('name')->get();
    $pendingVacations = Vacation::with('user')
        ->where('status', 'pending')
        ->where('end_date', '>=', now())
        ->get();
    
    $approvedVacations = Vacation::with('user')
        ->where('status', 'approved')
        ->where('end_date', '>=', now())
        ->get();

    return view('admin.verwaltung.vacations', compact('users', 'pendingVacations', 'approvedVacations'));
}

public function adminStore(Request $request)
{
    try {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,approved,rejected',
            'reason' => 'nullable|string'
        ]);

        // Default vacation type (adjust ID as needed)
        $vacation_type_id = 1; 

        $vacation = Vacation::create([
            'user_id' => $validated['user_id'],
            'vacation_type_id' => $vacation_type_id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'reason' => $request->input('note'), // Map note to reason
        ]);

        \Log::info('Vacation created:', $vacation->toArray());

        return redirect()->back()->with('success', 'Urlaub wurde erstellt');
    } catch (\Exception $e) {
        \Log::error('Vacation creation failed:', [
            'error' => $e->getMessage(),
            'request' => $request->all()
        ]);
        return redirect()->back()->with('error', 'Fehler beim Erstellen des Urlaubs: ' . $e->getMessage());
    }
}

public function adminEdit(Vacation $vacation)
{
    return response()->json([
        'id' => $vacation->id,
        'user_id' => $vacation->user_id,
        'vacation_type_id' => $vacation->vacation_type_id,
        'start_date' => $vacation->start_date->format('Y-m-d'),
        'end_date' => $vacation->end_date->format('Y-m-d'),
        'status' => $vacation->status,
        'reason' => $vacation->reason
    ]);
}

public function adminUpdate(Request $request, Vacation $vacation)
{
    $validated = $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'status' => 'required|in:pending,approved,rejected',
        'note' => 'nullable|string'
    ]);

    $vacation->update($validated);

    return response()->json(['success' => true]);
}
}