<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::all();
        return view('admin.ausruestung.lager', compact('equipment'));
    }

    public function show($id)
    {
        try {
            $equipment = Equipment::with(['logs' => function($query) {
                $query->latest();
            }])->findOrFail($id);
            
            return response()->json($equipment);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Equipment nicht gefunden'], 404);
        }
    }

    public function edit($id)
    {
        try {
            $equipment = Equipment::findOrFail($id);
            return response()->json($equipment);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Equipment nicht gefunden'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key_name' => 'required|unique:equipment',
            'display_name' => 'required',
            'category' => 'required',
            'description' => 'nullable',
            'stock' => 'required|integer|min:0',
            'is_consumable' => 'boolean'
        ]);

        Equipment::create($validated);
        return response()->json(['success' => true]);
    }

    public function update(Request $request, Equipment $equipment)
{
    $validated = $request->validate([
        'key_name' => 'required|unique:equipment,key_name,' . $equipment->id,
        'display_name' => 'required',
        'category' => 'required',
        'description' => 'nullable',
        'stock' => 'required|integer|min:0',
        'is_consumable' => 'boolean'
    ]);

    $oldStock = $equipment->stock;
    $newStock = $validated['stock'];
    $stockChange = $newStock - $oldStock;

    if ($stockChange !== 0) {
        EquipmentLog::create([
            'user_id' => Auth::id(),
            'equipment_id' => $equipment->id,
            'quantity' => $stockChange,
            'action' => $request->note ?? 'Bestand aktualisiert',
            'changed_by' => Auth::id()
        ]);
    }

    $equipment->update($validated);
    return redirect()->route('admin.equipment.index')->with('success', 'Equipment erfolgreich aktualisiert');
}

    public function destroy(Equipment $equipment)
    {
        try {
            $equipment->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Fehler beim Löschen'], 500);
        }
    }

    public function history($id)
    {
        \Log::info('Fetching history for equipment ID: ' . $id); // Debug log
        
        try {
            $logs = EquipmentLog::with(['user', 'changer'])
                ->where('equipment_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            \Log::info('Found logs: ', ['count' => $logs->count()]); // Debug log
            
            if ($logs->isEmpty()) {
                return response()->json(['message' => 'Keine Einträge gefunden'], 200);
            }
            
            return response()->json($logs);
        } catch (\Exception $e) {
            \Log::error('Error fetching history: ' . $e->getMessage()); // Debug log
            return response()->json(['error' => 'Verlauf nicht gefunden'], 404);
        }
    }
}