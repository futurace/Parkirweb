@extends('layouts.app', ['title' => 'Vehicle Type', 'active' => 'vehicle-types'])

@section('top-actions')
    <a class="btn btn-pink btn-small" href="{{ route('vehicle-types.create') }}">＋ ADD NEW VEHICLE TYPE</a>
@endsection

@section('content')
    <div class="panel table-panel">
        <h2 class="section-title"><strong>Vehicle Type</strong> Data Table</h2>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 220px;">NO.</th>
                        <th>VEHICLE TYPE</th>
                        <th class="text-center">FIRST HOUR CHARGES</th>
                        <th class="text-center">NEXT HOURLY CHARGES</th>
                        <th class="text-center">MAX COST PER DAY</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicleTypes as $index => $vehicleType)
                        <tr>
                            <td>
                                {{ $index + 1 }}.
                                <a class="edit-link" href="{{ route('vehicle-types.edit', $vehicleType) }}">✏ EDIT</a>
                                <form action="{{ route('vehicle-types.destroy', $vehicleType) }}" method="POST" style="display: inline;" onsubmit="confirmDelete(event, this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">🗑 DELETE</button>
                                </form>
                            </td>
                            <td>{{ $vehicleType->name }}</td>
                            <td class="text-center">Rp {{ number_format($vehicleType->first_hour_charges, 0, ',', '.') }}</td>
                            <td class="text-center">Rp {{ number_format($vehicleType->next_hourly_charges, 0, ',', '.') }}</td>
                            <td class="text-center">Rp {{ number_format($vehicleType->max_cost_per_day, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 30px; color: var(--muted);">
                                No vehicle types found. Click "+ ADD NEW VEHICLE TYPE" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
