@extends('layouts.main')
@section('title', 'Monthly Expenses | Jmitra & Co. Pvt. Ltd')
@section('content')	
<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<form action="{{ route('generate.monthly.expenses') }}"method="POST">
    					@csrf
    					<div class="row">
	    					<label>Generates expense by Month:</label>
							<div class="input-group mb-6">
							    <input type="month" name="generate_monthly_expense" id="generate_monthly_expense" class="form-control" placeholder="Select month" required>
							    <button class="btn btn-primary me-3 shadow" type="submit">
							        <i class="fa-solid fa-plus me-1"></i>
							    </button>
							</div>
						</div>
					</form>
					
					

					
					<form action="{{ route('form_submit_of_selected_month') }}"method="POST">
    					@csrf
    					<div class="row">
	    					<label>Form submit of selected month:</label>
							<div class="input-group mb-6">
							    <input type="month" name="formSubmitSelectedMonth" id="formSubmitSelectedMonth" class="form-control" placeholder="Select month" required>
							    <button class="btn btn-primary me-3 shadow" type="submit" >
							        <i class="fa-solid fa-eye me-1"></i>
							    </button>
							</div>
						</div>
					</form>

					
					@if(isset($UserExpenseOtherRecords) && $UserExpenseOtherRecords->is_submitted == 1)

						<form action="{{ route('form_submit_of_selected_month_final_print') }}"method="POST">
    						@csrf
    						<!--<input type="month" name="formSubmitSelectedMonth" id="formSubmitSelectedMonth" class="form-control" placeholder="Select month" value="{{ date('Y-m') }}" hidden>-->
							<button class="btn add me-3 shadow" type="submit" > Preview Submission</button>
						</form>

						<form action="{{ route('form_submit_of_selected_month_final_print') }}"method="POST">
    						@csrf
    						<input type="month" name="formSubmitSelectedMonth" id="formSubmitSelectedMonth" class="form-control" placeholder="Select month" value="{{ date('Y-m') }}" hidden>
							<button class="btn add shadow final-print" type="submit" > Final Print</button>

						</form>

					@endif

				</div>
			</div>
			@can('monthly-expense-search')
				@include('includes.search')
			@endcan
			@include('includes.message')
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Monthly Expenses</h5>
					</div>
					<div class="table-responsive">
						<table id="example" class="table bg-white rounded shadow-sm table-hover">
						  <thead>
						    <tr>
						    	<th scope="col">ID </th>
						      <th scope="col">Expense Date </th>
						      <th scope="col">Employee ID </th>
						      <th scope="col">Employee Name </th>
						      <th scope="col">Expense ID </th>
						      <th scope="col">Company Name </th>
						      <th scope="col">Month </th>
						      <th scope="col">Action </th>
						    </tr>
						  </thead>
						  <tbody>
						    
						    
							  @php $i = 0; @endphp
					            @foreach ($monthly_expenses as $key => $monthly_expense)
					            <tr>
					            	<td>{{ ++$key }}
					                	
									</td>
					                
					                <td>{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('d') }}
									</td>
						             <td class="text-truncate" style="max-width: 200px;">
						            @if (Auth::user()->userDetail && Auth::user()->userDetail->employee_id)
	                                    {{ Auth::user()->userDetail->employee_id }}
	                                @else
	                                    N/A
	                                @endif

	                            	</td>
					                <td>@if (Auth::user()->userDetail && Auth::user()->name)
	                                    {{ Auth::user()->name }}
	                                @else
	                                    N/A
	                                @endif</td>
					                <td>{{ $monthly_expense->expense_id }}</td>
					                <td>
					                	@if (Auth::user()->userDetail && Auth::user()->userDetail->companyMaster->company_name )
	                                    {{ Auth::user()->userDetail->companyMaster->company_name  }}
		                                @else
		                                    N/A
		                                @endif
					                </td>
					                <td>{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format("F' Y") }}
					                	
									</td>
					                <td>
					                    <div class="d-flex">
					                        
					                        <span>
					                            <a href="{{ route('monthly_expenses.edit', $monthly_expense->id) }}">
					                                <i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2"></i>
					                            </a>
					                        </span>
					                       
					                        <!-- <form method="POST" action="{{ route('monthly_expenses.destroy', $monthly_expense->id) }}" style="display:inline" id="deleteForm-{{ $monthly_expense->id }}">
					                            @csrf
					                            @method('DELETE')
					                            <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
					                               style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $monthly_expense->id }}" 
					                               title="Delete mode of expense"></i>
					                        </form> -->
					                       <!--  <div class="modal" tabindex="-1" id="deleteModal-{{ $monthly_expense->id }}">
					                            <div class="modal-dialog modal-dialog-centered">
					                                <div class="modal-content">
					                                    <div class="modal-header">
					                                        <h5 class="modal-title">Delete</h5>
					                                        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
					                                    </div>
					                                    <div class="modal-body">
					                                        <h4>Are you sure you want to delete this File ?</h4>
					                                    </div>
					                                    <div class="modal-footer">
					                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					                                        <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $monthly_expense->id }}').submit();">Yes, Delete</button>
					                                    </div>
					                                </div>
					                            </div>
					                        </div> -->
					                       
					                        
					                        
					                        <!-- <span>
												<i class="fa-solid fa-plus bg-primary text-white p-1 rounded-circle shadow" data-bs-toggle="modal" data-bs-target="#addModal"></i>
											</span> -->
					                    </div>
					                </td>
					            </tr>
					            
					            

								

								

					            @endforeach
						  </tbody>
						</table>
					</div>
				</div>
				<!--*******************
					 Pagination Start
		 		*****************-->
				@include('includes.pagination')
				<!--*******************
					 Pagination End
		 		*****************-->

		 		


				
				
				
				
				<!--*******************
					   Submission Promising Start
				*****************-->
				<div class="modal" tabindex="-1" id="submissionPromisiingModal">
					<div class="modal-dialog modal-dialog-centered">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Submission Promising</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<h6>Please Re-Check your work before Final Submission. After Final Submission you will not be able to modify it.</h6>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success">Final Submit</button>
						</div>
					  </div>
					</div>
				  </div>
				  <!--*******************
					   Submission Promising End
				*****************-->
				<!--*******************
					   Justification Start
				*****************-->
				<div class="modal" tabindex="-1" id="justificationModal">
					<div class="modal-dialog modal-dialog-centered">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Justification</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<h6>Justification if there is any Delay in Submission.</h6>
							<input type="text" class="form-control">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success">Final Submit</button>
						</div>
					  </div>
					</div>
				  </div>
				  <!--*******************
					   Justification End
				*****************-->
				<!--*******************
					   Preview Submission Start
				*****************-->
				
				<!--*******************
					   Preview Submission End
				*****************-->
			</div>

		</div>
		<!--*******************
			Main Content End
		 *****************-->


<script>
	

document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('Agreement');
        const submitButton = document.getElementById('submitButton');

        // Add an event listener to toggle the submit button
        checkbox.addEventListener('change', function() {
            submitButton.disabled = !checkbox.checked;
        });
    });
</script>
@endsection
@section('additional_style')
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCkXM3qxBSkyQDCrmTq1wDL3ZSXXi8nQ7Y&libraries=places" loading=async></script> -->
    

@endsection
@section('additional_script')


<!-- <script>
async function fetchDistanceMatrixOnClick() {
    const from = document.getElementById('from').value.trim();
    const to = document.getElementById('to').value.trim();

    if (!from || !to) {
        alert('Please enter both "From" and "To" locations.');
        return;
    }

    try {
        console.log('Sending request to server...');
        const response = await fetch('/get-distance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ from, to }),
        });

        const data = await response.json();
        console.log('Server response:', data);

        if (response.ok) {
            const distance = data.rows?.[0]?.elements?.[0]?.distance;

            if (distance && distance.value) {
                const distanceInKm = (distance.value / 1000).toFixed(2);
                document.getElementById('km_as_per_google_map').value = distanceInKm;
            } else {
                alert('Unable to retrieve distance.');
            }
        } else {
            alert('Error fetching data: ' + (data.error || 'Unknown Error'));
        }
    } catch (error) {
        console.error('Error fetching distance matrix:', error);
        alert('An error occurred while fetching the distance.');
    }
}

// Add event listener to the km_as_per_google_map input field
document.getElementById('km_as_per_google_map').addEventListener('click', fetchDistanceMatrixOnClick);
</script> -->



@endsection
