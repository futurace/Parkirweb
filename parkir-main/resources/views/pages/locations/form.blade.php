@extends('layouts.app', ['title' => 'Location', 'active' => 'locations'])

@section('content')
    @php
        $isEdit = isset($location);
    @endphp

    <div class="panel form-panel">
        <h2 class="section-title"><strong>Location</strong> {{ $isEdit ? 'Edit' : 'Input' }} Form</h2>

        <form method="POST" action="{{ $isEdit ? route('locations.update', $location) : route('locations.store') }}">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="field">
                <label for="location_name">Location Name</label>
                <input id="location_name" name="location_name" type="text" value="{{ old('location_name', $isEdit ? $location->name : '') }}" required>
                @error('location_name')
                    <span style="color: #ff1212; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="max_motorcycle">Max Motorcycle</label>
                <input id="max_motorcycle" name="max_motorcycle" type="number" value="{{ old('max_motorcycle', $isEdit ? $location->max_motorcycle : '') }}" required min="0">
                @error('max_motorcycle')
                    <span style="color: #ff1212; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="max_car">Max Car</label>
                <input id="max_car" name="max_car" type="number" value="{{ old('max_car', $isEdit ? $location->max_car : '') }}" required min="0">
                @error('max_car')
                    <span style="color: #ff1212; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="max_truck">Max Truck/Bus/Other</label>
                <input id="max_truck" name="max_truck" type="number" value="{{ old('max_truck', $isEdit ? $location->max_truck_bus_other : '') }}" required min="0">
                @error('max_truck')
                    <span style="color: #ff1212; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="button-row">
                <a class="btn btn-dark" href="{{ route('locations.index') }}">Cancel</a>
                <button class="btn btn-pink" type="submit">{{ $isEdit ? 'Update Location' : 'Save Location' }}</button>
            </div>
        </form>
    </div>
@endsection
