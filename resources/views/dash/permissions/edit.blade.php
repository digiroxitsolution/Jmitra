@extends('layouts.main')

@section('title', 'Permissions Update | Luis N Vaya ')



@section('content')



                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ __('global.Edit') }} {{ __('global.Permissions') }}</h1>
                        @can('permissions-create')
            <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"  href="{{ route('permissions.index') }}">{{ __('global.Back') }}</a>
            @endcan
                        
                    </div>


@can('permissions-store')


<div class="container">
@if(count($errors) > 0)
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
<div class="container">
<div class="card m-b-30">       
<div class="card-body">
<form action="{{ route('permissions.update', $permission->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('global.Name') }}:</strong>
                <input type="text" name="name" value="{{ old('name', $permission->name) }}" placeholder="Name" class="form-control">
            </div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('global.Roles') }}:</strong>
                <br/>
                @foreach($role as $value)
                    <label>
                        <input type="checkbox" name="role[]" value="{{ $value->id }}" 
                        {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }} class="name">
                        {{ $value->name }}
                    </label>
                    <br/>
                @endforeach
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">{{ __('global.Submit') }}</button>
        </div>
    </div>
</form>

</div>
</div>
</div>
@else

@include('forbidden.forbidden')

@endcan
  
@endsection

@section('extra_js')

@endsection

@section('additional_css')
@endsection