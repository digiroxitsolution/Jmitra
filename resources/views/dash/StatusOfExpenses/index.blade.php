@extends('layouts.main')
@section('title', 'Status Of Expenses| Jmitra & Co. Pvt. Ltd')
@section('content')
@can('status-of-expense')
<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<a href="{{ route('status_of_expenses', ['status' => 3]) }}" class="btn add shadow me-2">
				        Rejected
				    </a>
				    <a href="{{ route('status_of_expenses', ['status' => 2]) }}" class="btn add shadow me-2">
				        In Progress
				    </a>
				    <a href="{{ route('status_of_expenses', ['status' => 1]) }}" class="btn add shadow">
				        Completed
				    </a>

					
				</div>
			</div>
				@include('includes.search')
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Status of Expenses</h5>
					</div>
					<div class="table-responsive"> 

						

						@if(auth()->user()->hasRole('Super Admin'))
						    @include('dash.StatusOfExpenses.superAdmin')

						@elseif(auth()->user()->hasRole('Sales Admin'))
						    
							@include('dash.StatusOfExpenses.salesAdmin')

						@elseif(auth()->user()->hasRole('Sales Admin Hod'))
						    
							@include('dash.StatusOfExpenses.salesAdminHod')

						@elseif(auth()->user()->hasRole('Sales'))
						    
							@include('dash.StatusOfExpenses.sales')

						@else

							@include('dash.StatusOfExpenses.others')

						@endif



					</div>
				</div>
				<!--*******************
					 Pagination Start
		 		*****************-->
					@include('includes.pagination')
				<!--*******************
					 Pagination End
		 		*****************-->
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->		
@else
@include('forbidden.forbidden')
@endcan
@endsection
