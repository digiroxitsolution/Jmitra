<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@include('includes.search')
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Process Time Report</h5>
					</div>
					<div class="table-responsive">
						<table id="example" class="table bg-white rounded shadow-sm table-hover">
						  <thead>
						    <tr>
						      <th scope="col">S.No. </th>
						      <th scope="col">Employee ID </th>
						      <th scope="col">Name </th>
						      <th scope="col">HOD Name </th>
						      <th scope="col">Company Name </th>
						      <th scope="col">Location </th>
						      <th scope="col">Expense ID </th>
						      <th scope="col">Month of Expense </th>
						      <th scope="col">Status </th>
						      <th scope="col">Submission Date </th>
						      <th scope="col">Days Elapsed (Submission) </th>
						    </tr>
						  </thead>
						  <tbody>
						  	@php $i=1 @endphp
						  	@foreach ($monthly_expenses as $key => $monthly_expense)
						    <tr>
						      <td scope="col">{{ $i++ }}</td>
						      <td>@if (Auth::user()->userDetail && Auth::user()->userDetail->employee_id)
	                                    {{ Auth::user()->userDetail->employee_id }}
	                                @else
	                                    N/A
	                                @endif</td>
						      <td>@if (Auth::user()->userDetail && Auth::user()->name)
	                                    {{ Auth::user()->name }}
	                                @else
	                                    N/A
	                                @endif</td>
						      <td>{{ Auth::user()->userDetail->hod->name ?? 'N/A' }}</td>
						      <td>
						      	@if (Auth::user()->userDetail && Auth::user()->userDetail->companyMaster->company_name )
	                                    {{ Auth::user()->userDetail->companyMaster->company_name  }}
		                                @else
		                                    N/A
		                                @endif
		                        </td>
		                       <td>@if (Auth::user()->userDetail && Auth::user()->userDetail->locationMaster->working_location )
	                                    {{ Auth::user()->userDetail->locationMaster->working_location  }}
		                                @else
		                                    N/A
		                                @endif</td>
						      <td>{{ $monthly_expense->expense_id }}</td>
						      <td>{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('F') }}</td>
						      <td>
						      		@if ($monthly_expense->status == 3)
								        <span class="text-danger">Rejected</span>
								    @elseif ($monthly_expense->status == 2)
								        <span class="text-warning">In Progress</span>
								    @elseif ($monthly_expense->status == 1)
								        <span class="text-success">Completed</span>
								    @elseif ($monthly_expense->status == 0)
								        <span class="text-info">Not Submitted</span>
								    @endif
						      </td>
							  <td>{{ $monthly_expense->date_of_submission ? \Carbon\Carbon::parse($monthly_expense->date_of_submission)->format('d-m-Y') : 'N/A' }}
								</td>
							  <td>{{ $monthly_expense->days_elapsed }}</td>
						    </tr>
						    @endforeach
						  </tbody>
						</table>
					</div>
				</div>
				@include('includes.pagination')
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->