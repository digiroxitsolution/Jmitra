@extends('layouts.main')

@section('title', 'Role List | Jmitra & Co. Pvt. Ltd ')



@section('content')






@can('roles-list')                  

<div class="container-fluid px-4">
    <div class="row g-3 my-2">
                <div class="d-grid d-flex justify-content-end">
                    <a class=""  href="{{ route('roles.create') }}">
                        <button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addAndEditRejectedReasonModal"><i class="fa-solid fa-plus me-1"></i> {{ __('global.Create') }} {{ __('global.New') }} {{ __('global.Role') }}</button>
                    </a>
                </div>
            </div>
            @can('roles-search') 
                @include('includes.search')
            @endcan

            @include('includes.message')
            <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                            <h5 class="text-white">{{ __('global.Role') }} {{ __('global.Management') }}</h5>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table bg-white rounded shadow-sm table-hover">
                            <thead>
                              <tr>
                                 <th scope="col">{{ __('global.No') }}</th>
                                 <th scope="col">{{ __('global.Name') }}</th>
                                 <th width="280px">Action</th>
                              </tr>
                            <thead>
                            <tbody>   
                                @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @can('roles-show')
                                        <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">{{ __('global.Show') }}</a>
                                        @endcan
                                        @can('roles-edit')
                                            <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">{{ __('global.Edit') }}</a>
                                        @endcan
                                        @can('roles-delete')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
                @include('includes.pagination')
            </div>
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