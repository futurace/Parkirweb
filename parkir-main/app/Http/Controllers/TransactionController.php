<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\VehicleType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $locations = Location::orderBy('id', 'asc')->get();
        
        // Calculate parked count for each vehicle type per location
        foreach ($locations as $location) {
            $location->parked_motorcycle = Transaction::where('location_id', $location->id)
                ->where('status', 'parked')
                ->whereHas('vehicleType', function ($query) {
                    $query->where('name', 'Motorcycle');
                })->count();

            $location->parked_car = Transaction::where('location_id', $location->id)
                ->where('status', 'parked')
                ->whereHas('vehicleType', function ($query) {
                    $query->where('name', 'Car');
                })->count();

            $location->parked_truck = Transaction::where('location_id', $location->id)
                ->where('status', 'parked')
                ->whereHas('vehicleType', function ($query) {
                    $query->where('name', 'Truck/Bus/Other');
                })->count();
        }

        $vehicleTypes = VehicleType::all();
        
        $parkedTransactions = Transaction::with(['vehicleType', 'location'])
            ->where('status', 'parked')
            ->orderBy('entry_time', 'desc')
            ->get();

        return view('pages.transactions.index', compact('locations', 'vehicleTypes', 'parkedTransactions'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('transactions.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'ticket_number' => 'required|string|unique:transactions,ticket_number',
            'police_number' => 'required|string|max:20',
        ]);

        $location = Location::findOrFail($request->location_id);
        $vehicleType = VehicleType::findOrFail($request->vehicle_type_id);

    
        $parkedCount = Transaction::where('location_id', $location->id)
            ->where('status', 'parked')
            ->where('vehicle_type_id', $vehicleType->id)
            ->count();

        $limit = 0;
        $typeName = strtolower($vehicleType->name);
        if ($typeName === 'motorcycle') {
            $limit = $location->max_motorcycle;
        } elseif ($typeName === 'car') {
            $limit = $location->max_car;
        } else {
            $limit = $location->max_truck_bus_other;
        }

        if ($parkedCount >= $limit) {
            return redirect()->back()->withErrors(['capacity' => "Parking capacity is full for {$vehicleType->name} at {$location->name}!"])->withInput();
        }

        Transaction::create([
            'location_id' => $request->location_id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'ticket_number' => $request->ticket_number,
            'plate_number' => $request->police_number,
            'entry_time' => now(),
            'status' => 'parked',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Vehicle entered successfully! Ticket: ' . $request->ticket_number);
    }

    public function exit(Request $request): RedirectResponse
    {
        $request->validate([
            'ticket_number' => 'required|string',
        ]);

        $transaction = Transaction::where('ticket_number', $request->ticket_number)
            ->where('status', 'parked')
            ->first();

        if (!$transaction) {
            return redirect()->back()->withErrors(['ticket_number' => 'Active transaction with this ticket number not found.'])->withInput();
        }

        $entryTime = $transaction->entry_time;
        $exitTime = now();

        
        $durationMinutes = ceil($entryTime->diffInSeconds($exitTime) / 60);
        if ($durationMinutes <= 0) {
            $durationMinutes = 1;
        }

       
        $vehicleType = $transaction->vehicleType;
        $first = $vehicleType->first_hour_charges;
        $next = $vehicleType->next_hourly_charges;
        $max = $vehicleType->max_cost_per_day;

     
        $days = floor($durationMinutes / 24);
        $remainingMinutes = $durationMinutes % 24;

        $remainingCost = 0;
        if ($remainingMinutes > 0) {
            $remainingCost = $first + ($remainingMinutes - 1) * $next;
            if ($remainingCost > $max) {
                $remainingCost = $max;
            }
        }

        $totalPrice = ($days * $max) + $remainingCost;

        $transaction->update([
            'exit_time' => $exitTime,
            'total_price' => $totalPrice,
            'status' => 'finished'
        ]);

        $locationName = $transaction->location ? $transaction->location->name : '-';
        $vehicleTypeName = $vehicleType->name;
        
        $durationStr = $durationMinutes . ' minute(s)';
        $priceStr = 'Rp ' . number_format($totalPrice, 0, ',', '.');
        
        $receiptMessage = "Receipt for Ticket: {$transaction->ticket_number}\\n" .
                          "Location: {$locationName}\\n" .
                          "Vehicle Type: {$vehicleTypeName}\\n" .
                          "Plate: {$transaction->plate_number}\\n" .
                          "Duration: {$durationStr}\\n" .
                          "Total Charge: {$priceStr}";

        return redirect()->route('transactions.index')->with('success', $receiptMessage);
    }

    public function edit(): RedirectResponse
    {
        return redirect()->route('transactions.index');
    }

    public function update(): RedirectResponse
    {
        return redirect()->route('transactions.index');
    }

    public function destroy(): RedirectResponse
    {
        return redirect()->route('transactions.index');
    }

    public function report(): View
    {
        return view('pages.transaction-report');
    }
}
