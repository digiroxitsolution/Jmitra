@extends('layouts.main')
@section('title', 'HOD Calculator | Jmitra & Co. Pvt. Ltd')
@section('content')	
<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			<div class="row my-5">
				<div class="col-lg-12 col-12">
					
					<div class="bg-white p-4">
						<form action="{{ route('hod_calc') }}" method="POST">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <label for="monthName" class="form-label">Select Month</label>
                                    <!-- <input type="month" name="monthName" id="monthName" class="form-control" value="{{ old('monthName', $monthName ?? '') }}"> -->
                                    <input type="month" name="monthName" id="monthName" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <label for="fromDate" class="form-label">From</label>
                                    <input type="date" name="fromDate" id="fromDate" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <label for="toDate" class="form-label">To</label>
                                    <input type="date" name="toDate" id="toDate" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary mt-4">Search</button>
                                </div>
                            </div>
                        </form>
					</div>
				</div>
				
                        </br>
                        </br>
                @if(isset($groupedByStateSales) && isset($groupedByStateExpenses))
				<div class="col-lg-12 col-12">
					<div class="border table-header p-4 position-relative rounded-top-4">
						<h5 class="text-white">Calc</h5>
					</div>
					<div class="bg-white p-4">
						<table  class="table table-bordered table-hover table-light">
							<tbody>
								<tr class="table-active fw-bold">
									<th>State <i class="fa-solid fa-sort ms-2"></i></th>
									<th>Amount <i class="fa-solid fa-sort ms-2"></i></th>
								</tr>
								@foreach($results as $key => $result)
                                <tr>
                                    
                                    <td>{{ $result['state' ]}}</td>                             
                                    <td>{{ $result['calc' ]}}</td>
                                    
                                </tr>
                                @endforeach
								
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-lg-12 col-12">
					<div class="border table-header p-4 position-relative rounded-top-4">
						<h5 class="text-white">State wise Total Expense Report</h5>
					</div>
					<div class="bg-white p-4">
						<table class="table table-bordered table-hover table-light">
							<tbody>
								<tr class="table-active fw-bold">
									<th>State <i class="fa-solid fa-sort ms-2"></i></th>
									<th>Amount <i class="fa-solid fa-sort ms-2"></i></th>
								</tr>
								@foreach($results as $key => $result)
								<tr>
									<td>{{ $result['state' ]}}</td>                             
                                    <td>{{ $result['total_expense' ]}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!--*******************
					 Pagination Start
		 		*****************-->
				
				<!--*******************
					 Pagination End
		 		*****************-->

		 		
				<div class="row my-5">
					<div class="col-12">
						<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">State wise S/E Expense Report</h5>
						</div>
						<div class="bg-white p-4">
							<table  class="table table-bordered table-hover table-light">
								<tbody>
									<tr class="table-active fw-bold">
										<th>State <i class="fa-solid fa-sort ms-2"></i></th>
										<th>Expense <i class="fa-solid fa-sort ms-2"></i></th>
										<th>Sales <i class="fa-solid fa-sort ms-2"></i></th>
										<th>S/E <i class="fa-solid fa-sort ms-2"></i></th>
									</tr>
									@foreach($combined_sales_expenses as $state => $data)
	                                <tr>
	                                    <td>{{ $state }}</td>
	                                    <td>Rs. {{ number_format($data['expenses'] ?? 0, 2) }}</td>
	                                    <td>Rs. {{ number_format($data['sales'] ?? 0, 2) }}</td>
	                                    <td>{{ $data['sales_expense_ratio'] }}</td>
	                                </tr>
	                                @endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>


				<!--*******************
					 Pagination Start
		 		*****************-->
				 @include('includes.pagination')
				<!--*******************
					 Pagination End
		 		*****************-->
				 <!-- <div class="row my-4">
					<img src="{{ asset('assets/calc.png') }}">
				</div> -->
			@endif
		</div>
		<!--*******************
			Main Content End
		 *****************-->

		
@endsection
