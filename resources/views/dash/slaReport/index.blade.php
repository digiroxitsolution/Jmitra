@extends('layouts.main')
@section('title', 'SLA Report | Jmitra & Co. Pvt. Ltd')
@section('content')
@can('sla-report')
<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">

			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<a href="{{ route('sla_report', ['sla_status' => 1]) }}"><button class="btn me-3 add shadow" type="button">Violated</button></a>

					<a href="{{ route('sla_report', ['sla_status' => 0]) }}"><button class="btn me-3 add shadow" type="button">Non Violated</button></a>

					<a href="{{ route('sla_report_hod_status', ['sla_status_of_approval' => 1]) }}"><button class="btn me-3 add shadow" type="button">Hod Violated</button></a>

					<a href="{{ route('sla_report_hod_status', ['sla_status_of_approval' => 0]) }}"><button class="btn add shadow" type="button">Hod Non Violated</button></a>
				</div>
			</div>
			@include('includes.search')
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">SLA Report</h5>
					</div>
					<div class="table-responsive">

						@if(auth()->user()->hasRole('Super Admin'))
						    @include('dash.slaReport.super_admin')
						@elseif(auth()->user()->hasRole('Sales Admin'))
						    @include('dash.slaReport.sales_admin')

						@elseif(auth()->user()->hasRole('Sales Admin Hod'))
						    @include('dash.slaReport.sales_admin_hod')

						@else
						    @include('dash.slaReport.for_users')
						@endif
						

					</div>
				</div>
				<!--*******************
					 Pagination Start
		 		*****************-->
					@include('includes.pagination')
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->
	
@else
@include('forbidden.forbidden')
@endcan
@endsection
