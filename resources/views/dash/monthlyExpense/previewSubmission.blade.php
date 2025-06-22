<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Fontawsome Icon -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

	<!-- jQuery CDN (required for AJAX) -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

	<!-- Normal CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
	<!-- Favicon Icon -->
	<link rel="icon" href="{{ asset('assets/images/Favicon.png') }}">
	<title>@yield('title', config('app.name', 'Jmitra & Co. Pvt. Ltd'))</title>
	
	

</head>
<body>
	<div class="d-flex" id="wrapper">
		

		<!-- Page Content + Nav Bar -->
		<div id="page-content-wrapper">
		<!--*******************
			Navbar start
		*******************-->
		
		<!--*******************
			Navbar end
		*******************-->
		 <!--*******************
			Main Content start
		*******************-->
		
			
			
			<div class="row">
				
				<!--*******************
					   Form Submit of Selected Month Start
				*****************-->
				<div class="card">
					<div class="card-dialog card-dialog-centered card-fullscreen">
					  
						<div class="card-header" style="background-color:#077fad; color:white;">
						  <h4 class="" style="height: 50px;">Form Submit of Selected Month </h4>
						  
						</div>
						<div class="card-body">
							<div class="row mb-4">
								<div class="col-lg-4 col-12">
									Compnay Name: @if (Auth::user()->userDetail && Auth::user()->userDetail->companyMaster->company_name )
	                                    {{ Auth::user()->userDetail->companyMaster->company_name  }}
		                                @else
		                                    N/A
		                                @endif
								</div>
								<div class="col-lg-4 col-12">
									Division: @if (Auth::user()->userDetail && Auth::user()->userDetail->DivisonMaster->name )
	                                    {{ Auth::user()->userDetail->DivisonMaster->name  }}
		                                @else
		                                    N/A
		                                @endif
								</div>
								<div class="col-lg-4 col-12">
									Month: {{ \Carbon\Carbon::parse($month)->format('F') }}
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-lg-4 col-12">
									Name: @if (Auth::user()->userDetail && Auth::user()->name)
	                                    {{ Auth::user()->name }}
	                                @else
	                                    N/A
	                                @endif
								</div>
								<div class="col-lg-4 col-12">
									Designation: @if (Auth::user()->userDetail && Auth::user()->userDetail->designation->name )
	                                    {{ Auth::user()->userDetail->designation->name  }}
		                                @else
		                                    N/A
		                                @endif
								</div>
								<div class="col-lg-4 col-12">
									Location: @if (Auth::user()->userDetail && Auth::user()->userDetail->locationMaster->working_location )
	                                    {{ Auth::user()->userDetail->locationMaster->working_location  }}
		                                @else
		                                    N/A
		                                @endif
								</div>
							</div>

							
							<div class="row mb-4">
								<table class="table table-bordered table-hover text-center table-light">
									<tbody>
										<tr class="table-active fw-bold">
											<td rowspan="2">Date</td>
											<td rowspan="2">From</td>
											<td rowspan="2">To</td>
											<td colspan="2">Time</td>
											<td rowspan="2">KM as per User</td>
											<td rowspan="2">KM as per Google</td>
											<td rowspan="2">Mode</td>
											<td rowspan="2">Fare Amount</td>
											<td colspan="2">Daily Allowances</td>
											<td rowspan="2">Postage</td>
											<td rowspan="2">TEL/TGM</td>
											<td colspan="2">Other Expenses</td>
											<td rowspan="2">Attendace</td>
										</tr>
										<tr class="table-active fw-bold">
											<td>DEP</td>
											<td>ARR</td>
											<td>Working</td>
											<td>N. Working</td>
											<td>Purpose</td>
											<td>Amount</td>
										</tr>
										@foreach ($monthly_expenses as $key => $monthly_expense)
										<tr>
											<td>{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('d-m-Y') }}</td>
											<td>{{ $monthly_expense->from }}</td>
											<td>{{ $monthly_expense->to }}</td>
											<td>
												{{ $monthly_expense->departure_time ? \Carbon\Carbon::parse($monthly_expense->departure_time)->format('d-m-Y') : '' }}
	                                            <hr>
	                                        	{{ $monthly_expense->departure_time ? \Carbon\Carbon::parse($monthly_expense->departure_time)->format('h:i A') : '' }}
											</td>
											<td>
												{{ $monthly_expense->arrival_time ? \Carbon\Carbon::parse($monthly_expense->arrival_time)->format('d-m-Y') : '' }}                                 
		                                        <hr>
		                                        {{ $monthly_expense->arrival_time ? \Carbon\Carbon::parse($monthly_expense->arrival_time)->format('h:i A') : '' }}
											</td>
											<td>{{ $monthly_expense->km_as_per_user ?? 'N/A' }}</td>
											<td>{{ $monthly_expense->km_as_per_google_map ?? 'N/A' }}</td>
											<td>{{ $monthly_expense->ModeofExpenseMaster->mode_expense  ?? 'N/A' }}</td>
											<td>{{ $monthly_expense->fare_amount ?? 'N/A' }}</td>

											<td>
											    @if ($monthly_expense->expense_type_master_id != 8)
											        {{ $monthly_expense->da_total }}
											    @endif
											</td>
											<td>
											    @if ($monthly_expense->expense_type_master_id == 8)
											        {{ $monthly_expense->da_total }}
											    @endif
											</td>

											<td>{{ $monthly_expense->postage ?? 'N/A' }}</td>
											<td></td>
											<td>{{ $monthly_expense->OtherExpenseMaster->other_expense ?? 'N/A' }}</td>
											<td>{{ $monthly_expense->other_expenses_amount ?? 'N/A' }}</td>
											<td class="table-active">View</td>
										</tr>
										@endforeach
										


										<tr>
											<td colspan="8" class="table-active">Total</td>
											<td>Rs. {{ $total_fare_amount ?? 'N/A' }}</td>
											<td>Rs.{{ $total_da_location_working }} </td>
											<td>Rs. {{ $total_da_location_not_working }} </td>									
											<td>Rs. {{ $total_postage ?? 'N/A' }}</td>
											<td>Rs. {{ $total_mobile_internet ?? 'N/A' }}</td>
											<td></td>
											<td>Rs. {{ $total_other_expense_amount ?? 'N/A' }}</td>
											<td>***</td>
										</tr>
										<tr>
											<td colspan="7"></td>
											<td class="table-active">RUPEES in Words</td>
											<td colspan="4">{{ $grand_total_in_words }}</td>
											<td class="table-active">Grand Total</td>
											<td colspan="3">Rs. {{ $grand_total }}</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row mb-4">
								<div class="col-lg-6 col-12 fw-bold">Signature of Field Staff: __________ __________ __________</div>
								<div class="col-lg-6 col-12 fw-bold">Signature of Manager: __________ __________ __________</div>
							</div>
							<div class="row mb-4">
								<table class="table table-bordered table-hover table-light mt-5">
									<tbody>
										<tr class="table-active fw-bold">
											<td>Total</td>
											<td colspan="2">Number</td>
										</tr>
										<tr>
											<td>Fare Amount</td>
											<td>{{ $total_fare_amount ?? 'N/A' }}</td>
											
										</tr>
										<tr>
											<td>DA (Location)</td>
											<td>{{ $total_da_location ?? 'N/A' }}</td>
											
										</tr>
										<tr>
											<td>DA (Ex-Location)</td>
											<td>{{ $total_da_ex_location ?? 'N/A' }}</td>
											
										</tr>
										<tr>
											<td>Postage</td>
											<td>{{ $total_postage ?? 'N/A' }}</td>
											
										</tr>
										<tr>
											<td>Mobile/ Internet</td>
											<td>{{ $total_mobile_internet ?? 'N/A' }}</td>
											
										</tr>
										<tr>
											<td>Print_Stationery</td>
											<td>{{ $total_print_stationery ?? 'N/A' }}</td>
											
										</tr>
										<tr>
											<td>Other Expenses Amount</td>
											<td>{{ $total_other_expense_amount ?? 'N/A' }}</td>
											
										</tr>
										<tr>
											<td class="text-primary">Grand Total</td>
											<td colspan="2"><input type="text" class="form-control" value="{{ $grand_total }}" disabled></td>
										</tr>


										<form action="{{ route('user_expense_other_record_update.update', $user_expense_other_record->id) }}" method="POST">
					                        @csrf
											<tr>
												<td class="text-primary">Advance Taken (If Any):</td>
												<td colspan="2">
													<input type="number" class="form-control" value="{{ $user_expense_other_record->advance_taken }}" name="advance_taken" id="advance_taken" {{ $user_expense_other_record->is_submitted === 1 ? 'disabled' : '' }} ></td>
											</tr>
											<tr>
												<td class="text-primary">Remark of Advance Taken</td>
												<td colspan="2">
													<input type="text" class="form-control" value="{{ $user_expense_other_record->remark_of_advance_taken }}" id="remark_of_advance_taken" name="remark_of_advance_taken" {{ $user_expense_other_record->is_submitted === 1 ? 'disabled' : '' }} >
												</td>
											</tr>
												<input type="hidden" name="formSubmitSelectedMonth" id="formSubmitSelectedMonth" value="{{ $formSubmitSelectedMonth }}">

												<button type="submit" id="hiddenSubmitButton" hidden>My Button</button>

										</form>


										<tr>
											<td class="text-primary">Balance Due to CO./EMP:</td>
											<td colspan="2"><input type="text" class="form-control" value="{{ $balance_dues }}" disabled></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row mb-4">
								<div class="form-check">
									<form action="{{ route('submit_user_monthly_expense', $user_expense_other_record->id) }}" method="POST">
					                        @csrf	
										<input class="form-check-input me-2 ms-1" type="checkbox" value="1" id="accept_policy" name="accept_policy" {{ $user_expense_other_record->accept_policy == 1 ? 'checked' : '' }} {{ $user_expense_other_record->is_submitted === 1 ? 'disabled' : '' }} >
										<button type="submit" id="hiddenSubmissionButton" hidden>My Button</button>

									</form>

									<label class="form-check-label text-muted" for="flexCheckIndeterminateAgree">
									  I hereby confirmed that I verified the Expenses and found OK as per Travel/ Daily Allowance Policy.
									</label>
								  </div>
							</div>
							<div class="row mb-4">
								<div class="col-lg-4 col-12">Submitted by<br>@if (Auth::user()->userDetail && Auth::user()->name)
	                                    {{ Auth::user()->name }}
	                                @else
	                                    N/A
	                                @endif<br>

	                                {{ $user_expense_other_record->date_of_submission ? \Carbon\Carbon::parse($user_expense_other_record->date_of_submission)->format('d-m-Y') : 'N/A' }}
								    <br>
								    {{ $user_expense_other_record->date_of_submission ? \Carbon\Carbon::parse($user_expense_other_record->date_of_submission)->format('h:i A') : 'N/A' }}

	                            </div>
								<div class="col-lg-4 col-12">Verified by<br>{{ $user_expense_other_record->verifiedBy?->name ?? 'N/A' }}<br>

									{{ $user_expense_other_record->verified_time ? \Carbon\Carbon::parse($user_expense_other_record->verified_time)->format('d-m-Y') : 'N/A' }}
								    <br>
								    {{ $user_expense_other_record->verified_time ? \Carbon\Carbon::parse($user_expense_other_record->verified_time)->format('h:i A') : 'N/A' }}
								</div>
								<div class="col-lg-4 col-12">Approved by<br>
									{{ $user_expense_other_record->approvedBy?->name ?? 'N/A' }}<br>

									{{ $user_expense_other_record->approved_time ? \Carbon\Carbon::parse($user_expense_other_record->approved_time)->format('d-m-Y') : 'N/A' }}
								    <br>
								    {{ $user_expense_other_record->approved_time ? \Carbon\Carbon::parse($user_expense_other_record->approved_time)->format('h:i A') : 'N/A' }}

								</div>
							</div>
						</div>
						<div class="card-footer" style="height: 50px;">

							
							<a href="{{ route('monthly_expenses.index')}}"><button type="button" class="btn btn-danger float-end ms-2" data-bs-dismiss="modal">Closed</button></a>
							
						</div>
					  
					</div>
				  </div>
				<!--*******************
					   Form Submit of Selected Month Start
				*****************-->
				
				

		 </div>
	</div>
</div>

		
		
	

	<!-- Bootstrap JS -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- Normal JS -->
	<script src="{{ asset('assets/js/script.js') }}"></script>

	<!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap JS (Optional) -->
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    
    <script>
	    
	</script>

<script>
    document.getElementById('externalSubmitButton').addEventListener('click', function() {
        // Trigger the submit button inside the form
        document.getElementById('hiddenSubmitButton').click();
    });


    document.getElementById('externalSubmissionButton').addEventListener('click', function() {
        // Trigger the submit button inside the form
        document.getElementById('hiddenSubmissionButton').click();
    });



    document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('accept_policy');
    const submitButton = document.getElementById('externalSubmissionButton');

    // Check the initial state based on checkbox value
    toggleSubmitButtonState();

    // Add event listener to enable/disable button when checkbox is changed
    checkbox.addEventListener('change', function () {
        toggleSubmitButtonState();
    });

    // Function to enable/disable the submit button based on checkbox state
    function toggleSubmitButtonState() {
        // If the checkbox is unchecked or form is submitted, disable the button
        if (!checkbox.checked || {{ $user_expense_other_record->is_submitted }} === 1) {
            submitButton.disabled = true;
        } else {
            submitButton.disabled = false;
        }
    }
});
</script>

		
</body>
</html>