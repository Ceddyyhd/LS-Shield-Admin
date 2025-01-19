<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\EventRegistration;
use Carbon\Carbon; // Add this import
use App\Models\DienstplanZeiten; // Add this import

class EventplanungController extends Controller
{
    public function index()
    {
        $events = Event::with('teamMembers')
                      ->where('status', 'in Planung')
                      ->orderBy('datum_uhrzeit', 'desc')
                      ->get();

        return view('admin.planung.eventplanung', compact('events'));
    }

    public function show($id)
{
    try {
        $event = Event::with([
            'teamMembers',
            'registeredEmployees.employee',
            'registeredEmployees' => function($query) use ($id) {
                $query->with(['dienstplan' => function($subQuery) use ($id) {
                    $subQuery->where('event_id', $id);
                }]);
            }
        ])->findOrFail($id);

        return view('admin.planung.eventplanung_akte', compact('event'));
    } catch (\Exception $e) {
        Log::error('Error loading event: ' . $e->getMessage());
        return back()->with('error', 'Event nicht gefunden');
    }
}

public function getTotalHours($id)
{
    $totalHours = DienstplanZeiten::where('event_id', $id)->sum('worked_hours');
    return response()->json([
        'total_hours' => number_format($totalHours, 2)
    ]);
}

public function saveTimes(Request $request, $id)
{
    try {
        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);
        
        if ($startTime->gt($endTime)) {
            throw new \Exception('Startzeit kann nicht nach der Endzeit liegen');
        }
        
        $workedHours = $startTime->diffInHours($endTime);

        $dienstplan = DienstplanZeiten::updateOrCreate(
            [
                'event_id' => $id,
                'employee_id' => $request->employee_id
            ],
            [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'worked_hours' => $workedHours
            ]
        );

        return response()->json([
            'success' => true,
            'worked_hours' => number_format($workedHours, 2)
        ]);
    } catch (\Exception $e) {
        Log::error('Error saving times: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function register(Request $request, $id)
{
    try {
        $registration = EventRegistration::create([
            'event_id' => $id,
            'employee_id' => $request->employee_id,
            'notizen' => $request->notizen
        ]);

        Log::info('New registration created', ['registration' => $registration]);
        
        return redirect()
            ->back()
            ->with('success', 'Mitarbeiter erfolgreich angemeldet');
    } catch (\Exception $e) {
        Log::error('Registration failed', [
            'error' => $e->getMessage(),
            'event_id' => $id,
            'employee_id' => $request->employee_id
        ]);
        
        return redirect()
            ->back()
            ->with('error', 'Fehler bei der Anmeldung: ' . $e->getMessage());
    }
}

public function unregister($id)
{
    try {
        EventRegistration::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function updateTeams(Request $request, $id)
{
    try {
        $event = Event::findOrFail($id);
        $event->update([
            'team_verteilung' => $request->teams
        ]);
        
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


    public function uploadImage(Request $request)
{
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('public/event-images');
        return response()->json([
            'url' => asset(Storage::url($path))
        ]);
    }
    return response()->json(['error' => 'No image found.'], 400);
}


    public function updateContent(Request $request, $id)
{
    $event = Event::findOrFail($id);
    $event->update([
        'summernote_content' => $request->summernote_content
    ]);
    
    return redirect()->back()->with('success', 'Content updated successfully');
}
    public function duplicate($id)
    {
        try {
            $event = Event::findOrFail($id);
            $newEvent = $event->replicate();
            $newEvent->status = 'in Planung';
            $newEvent->datum_uhrzeit = now();
            $newEvent->save();

            if ($event->teamMembers) {
                $newEvent->teamMembers()->attach($event->teamMembers->pluck('id'));
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error duplicating event: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Fehler beim Duplizieren'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event wurde erfolgreich gelöscht'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Fehler beim Löschen'], 500);
        }
    }
}