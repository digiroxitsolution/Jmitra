@extends('layouts.main')

@section('title', 'Role Create | Jmitra & Co. Pvt. Ltd ')



@section('content')


                    

@can('roles-store')
<div class="container">

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>{{ __('global.Whoops') }}!</strong> {{ __('global.input error') }}<br><br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif
</div>
<div class="container-fluid px-4">
    <div class="row g-3 my-2">
                <div class="d-grid d-flex justify-content-end">
                    <a class=""  href="{{ route('roles.index') }}">
                        <button class="btn add shadow" type="button" > {{ __('global.Back') }}</button>
                    </a>
                </div>
            </div>
    <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                            <h5 class="text-white">{{ __('global.Create') }} {{ __('global.New') }} {{ __('global.Role') }}</h5>
                    </div>
                        <div class="card m-b-30">       
                        <div class="card-body">
                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ __('global.Name') }}:</strong>
                                        <input type="text" name="name" placeholder="Name" class="form-control" value="{{ old('name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <strong>{{ __('global.Permission') }}:</strong>
                                    </div>
                                    @foreach($permission as $value)
                                        <div class="col-6 col-sm-4 col-md-2 mb-4">
                                            <div class="card p-3 bg-info bg-opacity-10 border border-info rounded-end permission-card" data-value="{{ $value->name }}">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <!-- Use Bootstrap's custom checkbox styling -->
                                                        <input type="checkbox" name="permission[]" value="{{ $value->name }}" class="form-check-input" id="permission-{{ $value->name }}">
                                                        <label class="form-check-label" for="permission-{{ $value->name }}">
                                                            {{ $value->display_name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn add shadow">{{ __('global.Submit') }}</button>
                            </div>
                        </form>

                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle card click to toggle checkbox
        $('.permission-card').on('click', function(e) {
            // Check if the click was on the checkbox or the card
            var checkbox = $(this).find('input[type="checkbox"]');
            if (!checkbox.prop('disabled')) {
                checkbox.prop('checked', !checkbox.prop('checked'));

                // Toggle card highlight based on checkbox state
                if (checkbox.prop('checked')) {
                    $(this).addClass('bg-success bg-opacity-25'); // Highlight card when selected
                } else {
                    $(this).removeClass('bg-success bg-opacity-25'); // Remove highlight when not selected
                }
            }
        });

        // Optional: Add a toggle when the checkbox itself is clicked
        $('input[type="checkbox"]').on('change', function() {
            var card = $(this).closest('.permission-card');
            if ($(this).prop('checked')) {
                card.addClass('bg-success bg-opacity-25'); // Highlight card when selected
            } else {
                card.removeClass('bg-success bg-opacity-25'); // Remove highlight when not selected
            }
        });
    });
</script>
@else

@include('forbidden.forbidden')

@endcan
  
@endsection

@section('extra_js')

@endsection

@section('additional_css')
@endsection