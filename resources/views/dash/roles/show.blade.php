@extends('layouts.main')

@section('title', 'Role Show | Jmitra & Co. Pvt. Ltd ')



@section('content')



@can('permissions-list') 

<div class="container-fluid px-4">
    <div class="row g-3 my-2">
                <div class="d-grid d-flex justify-content-end">
                    <a class=""  href="{{ route('roles.index') }}">
                        <button class="btn add shadow" type="button" ><i class="fa-solid fa-plus me-1"></i> {{ __('global.Back') }}</button>
                    </a>
                </div>
            </div>
            
    <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                            <h5 class="text-white">Roles Show</h5>
                    </div>
                    <div class="table-responsive">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>{{ __('global.Name') }}:</strong>
                                {{ $role->name }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>{{ __('global.Permissions') }}:</strong>
                                <div class="row">
                                    @if(!empty($rolePermissions))
                                            @foreach($rolePermissions as $v)
                                                <div class="col-md-2 mb-4">
                                                    <div class="card">
                                                        <div class="card-body text-center">
                                                            <i class="fa fa-check-circle fa-1x text-success mb-2"></i>
                                                            <h5 class="card-title text-truncate" style="font-size: 14px;" >{{ $v->display_name }}</h5>
                                                            <p class="card-text">
                                                                <label class="badge badge-success  p-1">{{ $v->display_name }}</label>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@else

@include('forbidden.forbidden')

@endcan
  
@endsection

