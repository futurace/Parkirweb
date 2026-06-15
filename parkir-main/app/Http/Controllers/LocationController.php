<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::orderBy('id', 'asc')->get();
        return view('pages.locations.index', compact('locations'));
    }

    public function report(): View
    {
        return view('pages.location-report');
    }

    public function create(): View
    {
        return view('pages.locations.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car' => 'required|integer|min:0',
            'max_truck' => 'required|integer|min:0',
        ]);

        Location::create([
            'name' => $request->location_name,
            'max_motorcycle' => $request->max_motorcycle,
            'max_car' => $request->max_car,
            'max_truck_bus_other' => $request->max_truck,
            'capacity' => 0,
        ]);

        return redirect()->route('locations.index')->with('success', 'New Location was successfully saved!');
    }

    public function edit(Location $location): View
    {
        return view('pages.locations.form', compact('location'));
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car' => 'required|integer|min:0',
            'max_truck' => 'required|integer|min:0',
        ]);

        $location->update([
            'name' => $request->location_name,
            'max_motorcycle' => $request->max_motorcycle,
            'max_car' => $request->max_car,
            'max_truck_bus_other' => $request->max_truck,
        ]);

        return redirect()->route('locations.index')->with('success', 'Location was successfully updated!');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location was successfully deleted!');
    }
}
