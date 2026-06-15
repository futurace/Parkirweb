@extends('layouts.app', ['title' => 'Vehicle Type', 'active' => 'vehicle-types'])

@section('content')
    @php
        $isEdit = isset($vehicleType);
    @endphp

    <div class="panel form-panel">
        <h2 class="section-title"><strong>Vehicle Type</strong> {{ $isEdit ? 'Edit' : 'Input' }} Form</h2>

        <form method="POST" action="{{ $isEdit ? route('vehicle-types.update', $vehicleType) : route('vehicle-types.store') }}">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="field">
                <label for="name">Vehicle Type</label>
                <select id="name" name="name" required>
                    <option value=""></option>
                    <option value="Motorcycle" {{ old('name', $isEdit ? $vehicleType->name : '') === 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                    <option value="Car" {{ old('name', $isEdit ? $vehicleType->name : '') === 'Car' ? 'selected' : '' }}>Car</option>
                    <option value="Truck/Bus/Other" {{ old('name', $isEdit ? $vehicleType->name : '') === 'Truck/Bus/Other' ? 'selected' : '' }}>Truck/Bus/Other</option>
                </select>
                @error('name')
                    <span style="color: #ff1212; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="first_hour_charges">First Hour Charges</label>
                <input id="first_hour_charges" name="first_hour_charges" type="number" value="{{ old('first_hour_charges', $isEdit ? $vehicleType->first_hour_charges : '') }}" required min="0">
                @error('first_hour_charges')
                    <span style="color: #ff1212; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="next_hourly_charges">Next Hourly Charges</label>
                <input id="next_hourly_charges" name="next_hourly_charges" type="number" value="{{ old('next_hourly_charges', $isEdit ? $vehicleType->next_hourly_charges : '') }}" required min="0">
                @error('next_hourly_charges')
                    <span style="color: #ff1212; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="max_cost_per_day">Max Cost Per Day</label>
                <input id="max_cost_per_day" name="max_cost_per_day" type="number" value="{{ old('max_cost_per_day', $isEdit ? $vehicleType->max_cost_per_day : '') }}" required min="0">
                @error('max_cost_per_day')
                    <span style="color: #ff1212; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="button-row">
                <a class="btn btn-dark" href="{{ route('vehicle-types.index') }}">Cancel</a>
                <button class="btn btn-pink" type="submit">{{ $isEdit ? 'Update Vehicle Type' : 'Save Vehicle Type' }}</button>
            </div>
        </form>
    </div>
@endsection
