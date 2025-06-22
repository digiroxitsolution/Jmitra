@extends('layouts.main')
@section('title', 'Process Times Report| Jmitra & Co. Pvt. Ltd')
@section('content')	
@can('process-time-report')



@if(auth()->user()->hasRole('Sales'))
    @include('dash.processTimeReport.for_users')

@elseif(auth()->user()->hasRole('Sales Admin'))
    @include('dash.processTimeReport.for_admins')

@elseif(auth()->user()->hasRole('Sales Admin Hod'))
    @include('dash.processTimeReport.for_admins_hod')

@elseif(auth()->user()->hasRole('Super Admin'))
    @include('dash.processTimeReport.superAdmin')

@else
    @include('dash.processTimeReport.for_users')
@endif
	
	

    	
@else
@include('forbidden.forbidden')
@endcan
@endsection
