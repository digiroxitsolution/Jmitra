@extends('layouts.main')
@section('title', 'Location Master | Jmitra & Co. Pvt. Ltd')
@section('content')
@can('location-update')
<div class="container mt-4 d-flex justify-content-center">
  <div class="card shadow-lg rounded" style="width: 40rem;">
    <div class="card-header table-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                <h5 class="mb-0">Update Location</h5>
                <a href="{{ route('location_master.index') }}" class="btn-close btn-close-white"></a>
            </div>
    <div class="card-body">
      <form action="{{ route('location_master.update', $locations->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Card Header -->
            

            <!-- Card Body -->
            <div class="card-body">
                @include('includes.message')

                <div class="row">
                    <!-- State Dropdown -->
                    <div class="col-md-6 mb-3">
                        <label for="state_id" class="form-label">State <span class="text-danger">*</span>:</label>
                        <select name="state_id" id="state_id" class="form-select">
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ $state->id == $locations->state_id ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City Dropdown -->
                    <div class="col-md-6 mb-3">
                        <label for="city_id" class="form-label">City <span class="text-danger">*</span>:</label>
                        <select name="city_id" id="city_id" class="form-select" required>
                            <option value="">Select City</option>
                            @if(isset($cities))
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $city->id == $locations->city_id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Working Location -->
                    <div class="col-12 mb-3">
                        <label for="working_location" class="form-label">Working Location <span class="text-danger">*</span>:</label>
                        <input type="text" class="form-control" id="working_location" name="working_location" value="{{ $locations->working_location }}" required>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('location_master.index') }}" class="btn btn-secondary me-2">Close</a>
                <button type="submit" class="btn btn-primary">Update Location</button>
            </div>
        </form>
    </div>
  </div>




</div>

@else

@include('forbidden.forbidden')

@endcan
@endsection