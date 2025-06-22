@extends('layouts.main')

@section('title', 'Permissions Show | Luis N Vaya ')



@section('content')



                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ __('global.Show') }} {{ __('global.Permission') }}</h1>
                        @can('permissions-create')
            <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"  href="{{ route('permissions.index') }}">{{ __('global.Back') }}</a>
            @endcan
                        
                    </div>


@can('permissions-store')

<div class="container">
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('global.Name') }}:</strong>
            {{ $permission->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('global.Roles') }}:</strong>
            @if(!empty($rolePermissions))
                @foreach($rolePermissions as $v)
                    <label class="label label-success">{{ $v->name }},</label>
                @endforeach
            @endif
        </div>
    </div>
</div>
</div>
@else

@include('auth.dashboard.forbidden.forbidden')

@endcan
  
@endsection

@section('extra_js')

@endsection

@section('additional_css')
@endsection