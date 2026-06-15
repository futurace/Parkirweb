<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VehicleTypeController extends Controller
{
    public function index(): View
    {
        $vehicleTypes = VehicleType::orderBy('id', 'asc')->get();
        return view('pages.vehicle-types.index', compact('vehicleTypes'));
    }

    public function create(): View
    {
        return view('pages.vehicle-types.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'first_hour_charges' => 'required|integer|min:0',
            'next_hourly_charges' => 'required|integer|min:0',
            'max_cost_per_day' => 'required|integer|min:0',
        ]);

        VehicleType::create([
            'name' => $request->name,
            'rate_per_hour' => 0,
            'first_hour_charges' => $request->first_hour_charges,
            'next_hourly_charges' => $request->next_hourly_charges,
            'max_cost_per_day' => $request->max_cost_per_day,
        ]);

        return redirect()->route('vehicle-types.index')->with('success', 'New Vehicle Type was successfully saved!');
    }

    public function edit(VehicleType $vehicleType): View
    {
        return view('pages.vehicle-types.form', compact('vehicleType'));
    }

    public function update(Request $request, VehicleType $vehicleType): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'first_hour_charges' => 'required|integer|min:0',
            'next_hourly_charges' => 'required|integer|min:0',
            'max_cost_per_day' => 'required|integer|min:0',
        ]);

        $vehicleType->update([
            'name' => $request->name,
            'first_hour_charges' => $request->first_hour_charges,
            'next_hourly_charges' => $request->next_hourly_charges,
            'max_cost_per_day' => $request->max_cost_per_day,
        ]);

        return redirect()->route('vehicle-types.index')->with('success', 'Vehicle Type was successfully updated!');
    }

    public function destroy(VehicleType $vehicleType): RedirectResponse
    {
        $vehicleType->delete();
        return redirect()->route('vehicle-types.index')->with('success', 'Vehicle Type was successfully deleted!');
    }
}
