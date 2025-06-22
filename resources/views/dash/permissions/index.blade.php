@extends('layouts.main')

@section('title', 'Users List | Luis N Vaya ')



@section('content')




@can('permissions-list')

<div class="container-fluid px-4">
    <div class="row my-5">
        @can('permissions-search') 
                @include('includes.search')
            @endcan
        @include('includes.message')
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                            <h5 class="text-white">Permission List</h5>
                    </div>
                    <div class="table-responsive">



                        <table id="example" class="table bg-white rounded shadow-sm table-hover">
                            <thead>
                              <tr>
                                 <th scope="col">{{ __('global.No') }}</th>
                                 <th scope="col">{{ __('global.Name') }}</th>
                                 
                                 
                                 <!-- <th width="280px">{{ __('global.Action') }}</th> -->
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key => $permission)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $permission->display_name }}</td>
                                    
                                    <!-- <td>
                                        @can('permissions-show')
                                        <a class="btn btn-info" href="{{ route('permissions.show',$permission->id) }}">{{ __('global.Show') }}</a>
                                        @endcan
                                        @can('permissions-edit')
                                            <a class="btn btn-primary" href="{{ route('permissions.edit',$permission->id) }}">{{ __('global.Edit') }}</a>
                                        @endcan


                                        @can('permissions-delete')
                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        @endcan

                                    </td> -->
                                </tr>
                                @endforeach
                            <tbody>
                        </table>

                        
                    </div>
                </div>

                @include('includes.pagination')
            </div>
        </div>
</div>



@else

@include('forbidden.forbidden')

@endcan
  
@endsection

