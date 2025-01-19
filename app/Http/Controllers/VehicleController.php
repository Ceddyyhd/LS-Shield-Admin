<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FinancialEntry;
use App\Models\Deckel;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('logs')->latest()->get();
        return view('admin.verwaltung.vehicles', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|string',
            'license_plate' => 'required|string|unique:vehicles',
            'location' => 'required|string',
            'next_inspection' => 'required|date',
            'notes' => 'nullable|string',
            'turbo_tuning' => 'boolean',
            'engine_tuning' => 'boolean',
            'transmission_tuning' => 'boolean',
            'brake_tuning' => 'boolean'
        ]);

        Vehicle::create($validated);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Fahrzeug wurde hinzugefügt');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'model' => 'required|string',
            'license_plate' => 'required|string|unique:vehicles,license_plate,' . $vehicle->id,
            'location' => 'required|string',
            'next_inspection' => 'required|date',
            'notes' => 'nullable|string',
            'turbo_tuning' => 'boolean',
            'engine_tuning' => 'boolean',
            'transmission_tuning' => 'boolean',
            'brake_tuning' => 'boolean',
            'decommissioned' => 'boolean'
        ]);

        $vehicle->update($validated);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Fahrzeug wurde aktualisiert');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Fahrzeug wurde gelöscht');
    }

    public function edit(Vehicle $vehicle)
{
    return response()->json([
        'id' => $vehicle->id,
        'model' => $vehicle->model,
        'license_plate' => $vehicle->license_plate,
        'location' => $vehicle->location,
        'next_inspection' => $vehicle->next_inspection->format('Y-m-d'), // Format for HTML date input
        'notes' => $vehicle->notes,
        'turbo_tuning' => (bool)$vehicle->turbo_tuning,
        'engine_tuning' => (bool)$vehicle->engine_tuning,
        'transmission_tuning' => (bool)$vehicle->transmission_tuning,
        'brake_tuning' => (bool)$vehicle->brake_tuning,
        'decommissioned' => (bool)$vehicle->decommissioned
    ]);
}

public function fuel(Request $request, Vehicle $vehicle)
{
    $validated = $request->validate([
        'price' => 'required|numeric|min:0',
        'fuel_location' => 'required|string'
    ]);

    if ($request->has('is_deckel')) {
        Deckel::create([
            'vehicle_id' => $vehicle->id,
            'notiz' => "Getankt Kennzeichen: {$vehicle->license_plate} in {$validated['fuel_location']}",
            'betrag' => $validated['price'],
            'erstellt_von' => Auth::id(),
            'location' => $validated['fuel_location']
        ]);
    } else {
        FinancialEntry::create([
            'type' => 'Ausgabe',
            'category' => 'Tanken',
            'note' => "{$request->user()->name} hat für {$vehicle->license_plate} in {$validated['fuel_location']} getankt",
            'amount' => $validated['price'],
            'user_id' => Auth::id()
        ]);
    }

    return redirect()->back()->with('success', 'Tankung wurde erfasst');
}
}