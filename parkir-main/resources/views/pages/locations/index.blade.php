@extends('layouts.app', ['title' => 'Location', 'active' => 'locations'])

@section('top-actions')
    <a class="btn btn-pink btn-small" href="{{ route('locations.create') }}">＋ ADD NEW LOCATION</a>
@endsection

@section('content')
    <div class="panel table-panel">
        <h2 class="section-title"><strong>Location</strong> Data Table</h2>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 220px;">NO.</th>
                        <th>LOCATION NAME</th>
                        <th class="text-center">MAX MOTORCYCLE</th>
                        <th class="text-center">MAX CAR</th>
                        <th class="text-center">MAX TRUCK/BUS/OTHER</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($locations as $index => $location)
                        <tr>
                            <td>
                                {{ $index + 1 }}.
                                <a class="edit-link" href="{{ route('locations.edit', $location) }}">✏ EDIT</a>
                                <form action="{{ route('locations.destroy', $location) }}" method="POST" style="display: inline;" onsubmit="confirmDelete(event, this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">🗑 DELETE</button>
                                </form>
                            </td>
                            <td>{{ $location->name }}</td>
                            <td class="text-center">{{ $location->max_motorcycle }}</td>
                            <td class="text-center">{{ $location->max_car }}</td>
                            <td class="text-center">{{ $location->max_truck_bus_other }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 30px; color: var(--muted);">
                                No locations found. Click "+ ADD NEW LOCATION" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
