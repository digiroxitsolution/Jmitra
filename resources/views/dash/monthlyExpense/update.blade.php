<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Fontawsome Icon -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- jQuery CDN (required for AJAX) -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCkXM3qxBSkyQDCrmTq1wDL3ZSXXi8nQ7Y&libraries=places" async></script>

	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

	<!-- Normal CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
	<!-- Favicon Icon -->
	<link rel="icon" href="{{ asset('assets/images/Favicon.png') }}">
	<title>Monthly Expenses | Jmitra & Co. Pvt. Ltd</title>
	<style type="text/css">
		#multi_location_wrapper, #return_input_wrapper {
            display: none; /* Initially hidden */
            margin-top: 10px;
        }
	</style>
	

</head>
<body>
	<div class="d-flex" id="wrapper">		
		<div id="page-content-wrapper">
		
		 <!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid">		
			
			@include('includes.message')
			<div class="row">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4 d-flex justify-content-between align-items-center">
					    <h5 class="text-white">Monthly Expenses Update</h5>
					    <a href="{{ route('monthly_expenses.index') }}">
					        <button type="button" class="btn-close text-white bg-white" aria-label="Close"></button>
					    </a>
					</div>


					<form action="{{ route('monthly_expenses.update', $monthly_expense->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                    
				        <div class="row">
    						<div class="col-md-6">
		                        <div class="card">
		                        	<div class="card-header" style="background-color:#077fad; color:white;">
									    Expenses
									  </div>
									  <div class="card-body">
										  <div class="row mb-4">
										   
										   <div class="col-lg-6 col-12">
										   	 <label for="expense_date" class="form-label">Expense Date<span class="text-danger">*</span>:</label>
											 <input type="date" class="form-control" id="expense_date" name="expense_date" placeholder="Expense Date" value="{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('Y-m-d') }}" readonly>
										   </div>
										   <div class="col-lg-6 col-12">
										   	 <label for="expense_type_master_id" class="form-label">Expense Type<span class="text-danger">*</span>:</label>
											 <select class="form-select" aria-label="Default select example" id="expense_type_master_id" name="expense_type_master_id" required>
											    <option value="" disabled selected>Select Expense Type</option>
											    @foreach ($expense_type_master as $expense_type)
											        <option value="{{ $expense_type->id }}" {{ $expense_type->id == $monthly_expense->expense_type_master_id ? 'selected' : '' }}>
											            {{ $expense_type->expense_type }}
											        </option>
											    @endforeach
											 </select>
																			   </div>
										  </div>
										  <div class="row mb-4">
										    <div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="one_way_two_way_multi_location" class="form-label">One Way/ Two Way/ Multi Location<span class="text-danger">*</span>:</label>
												<select class="form-select" aria-label="Default select example" id="one_way_two_way_multi_location" name="one_way_two_way_multi_location"  required>
												<option value="One Way" {{ $monthly_expense->one_way_two_way_multi_location === 'One Way' ? 'selected' : '' }}>One Way</option>
											    <option value="Two Way" {{ $monthly_expense->one_way_two_way_multi_location === 'Two Way' ? 'selected' : '' }}>Two Way</option>
											    <option value="Multi Location" {{ $monthly_expense->one_way_two_way_multi_location === 'Multi Location' ? 'selected' : '' }}>Multi Location</option>
												</select>
											</div>
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="from" class="form-label">From<span class="text-danger">*</span>:</label>
												<input type="text" class="form-control" id="from" name="from" placeholder="From" value="{{ $monthly_expense->from }}" required>
											</div>
										   </div>
										   <div class="row mb-4">
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="to" class="form-label">To<span class="text-danger">*</span>:</label>
												<input type="text" class="form-control" id="to" name="to" placeholder="To" value="{{ $monthly_expense->to }}"  required>
											</div>
											<!-- Multi-location field (hidden by default) -->
											
									        <div id="multi_location_wrapper" style="display: none; margin-top: 10px;" class="col-lg-6 col-12 mb-lg-0 mb-3">
									            <label for="multi_location" class="form-label">Multiple Locations:</label>
									            <input type="text" class="form-control" id="multi_location" placeholder="Type a location...">
									        </div>
									        <div id="return_input_wrapper" class="col-lg-6 col-12 mb-lg-0 mb-3>
                                                <label for="return_input" class="form-label">Return Location:</label>
                                                <input type="text" class="form-control" id="return_input" placeholder="Type return location..."
                								title="Enter 'From' location first." disabled>
                                            </div>
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="departure_time" class="form-label">Dep. Time:</label>
												<input type="datetime-local" class="form-control" id="departure_time" name="departure_time" placeholder="Dep. Time" value="{{ $monthly_expense->departure_time }}">
											</div>
										  </div>
										  <div class="row mb-4">
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="arrival_time" class="form-label">Arr. Time:</label>
												<input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time" placeholder="Arr. Time" value="{{ $monthly_expense->arrival_time }}">
											</div>
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="km_as_per_user" class="form-label">KM. as per User<span class="text-danger">*</span></label>
												<input type="number" class="form-control no-negative" id="km_as_per_user" name="km_as_per_user" placeholder="KM. as per User" value="{{ $monthly_expense->km_as_per_user }}">
											</div>
										  </div>
										  <div class="row mb-4">
										  	
											<div class="col-lg-6 col-12 mb-lg-0 mb-3" style="display:none;">
												<label for="km_as_per_google_map" class="form-label">KM. as per Google Map:<span class="text-danger">*</span></label>
												<input type="number" class="form-control no-negative" id="km_as_per_google_map" name="km_as_per_google_map" placeholder="KM. as per Google Map" value="{{ $monthly_expense->km_as_per_google_map }}" step="any" readonly>
											</div>

											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="mode_of_expense_master_id" class="form-label">Expense Mode:<span class="text-danger">*</span></label>
												<select class="form-select" aria-label="Default select example" id="mode_of_expense_master_id" name="mode_of_expense_master_id"  required>
													<option value="" disabled selected>Select Expense Mode</option>
													@foreach($expense_modes as $expense_mode)
				                                            <option value="{{ $expense_mode->id }}" {{ $expense_mode->id == $monthly_expense->mode_of_expense_master_id ? 'selected' : '' }}>
				                                                {{ $expense_mode->mode_expense }}
				                                            </option>
				                                        @endforeach
													</select>
											</div>
										  </div>
										  <div class="row">
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="fare_amount" class="form-label">Fare Amount:<span class="text-danger">*</span></label>
												<input type="number" class="form-control no-negative" id="fare_amount" name="fare_amount" placeholder="Fare Amount" value="{{ $monthly_expense->fare_amount }}">
											</div>
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="da_location" class="form-label">DA(Location):</label>
												<input type="number" class="form-control no-negative" id="da_location" name="da_location" placeholder="DA(Location)" value="{{ $monthly_expense->da_location }}" readonly>
											</div>
										  </div>
										</div>
								  </div>
								</div>



						  <div class="col-md-6">
							  <div class="card">
	                        	<div class="card-header" style="background-color:#077fad; color:white;">
								    Monthly Expenses
								  </div>
								  <div class="card-body">
									  <div class="row mb-4">
										 <div class="col-lg-6 col-12 mb-lg-0 mb-3">
											  <label for="da_ex_location" class="form-label">DA(Ex-Location):</label>
										   <input type="number" class="form-control no-negative" id="da_ex_location" name="da_ex_location" placeholder="DA(Ex-Location)" value="{{ $monthly_expense->da_ex_location }}" readonly>
										 </div>
										 <div class="col-lg-6 col-12">
											  <label for="da_outstation" class="form-label">DA(Outstation)<span class="text-danger">*</span>:</label>
										   <input type="number" class="form-control no-negative" id="da_outstation" name="da_outstation" placeholder="DA(Outstation)" value="{{ $monthly_expense->da_outstation }}" readonly>
										 </div>
										</div>
										<div class="row mb-4">
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="da_total" class="form-label">DA(Total)<span class="text-danger">*</span>:</label>
											 <input type="number" class="form-control no-negative" id="da_total" name="da_total" placeholder="DA(Total)" value="{{ $monthly_expense->da_total }}">
										  	 </div>
											<div class="col-lg-6 col-12">
													<label for="postage" class="form-label">Postage<span class="text-danger">*</span>:</label>
												<input type="number" class="form-control no-negative" id="postage" name="postage" placeholder="Postage" value="{{ $monthly_expense->postage }}">
											</div>
										 </div>
										 <div class="row mb-4">
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="mobile_internet" class="form-label">Mobile/Internet<span class="text-danger">*</span>:</label>
											 <input type="number" class="form-control no-negative" id="mobile_internet" name="mobile_internet" placeholder="Mobile/Internet" value="{{ $monthly_expense->mobile_internet }}">
										  	 </div>
											<div class="col-lg-6 col-12">
													<label for="print_stationery" class="form-label">Print Stationery<span class="text-danger">*</span>:</label>
												<input type="number" class="form-control no-negative" id="print_stationery" name="print_stationery" placeholder="Print Stationery" value="{{ $monthly_expense->print_stationery }}">
											</div>
										</div>
										<div class="row mb-4">
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="other_expense_master_id" class="form-label">Other Expenses Purpose<span class="text-danger">*</span>:</label>
												<select class="form-select" aria-label="Default select example" id="other_expense_master_id" name="other_expense_master_id">
													<option value="" disabled selected>Select Other Expenses Purpose</option>

													@foreach($other_expense_master as $other_expense)
			                                            <option value="{{ $other_expense->id }}" {{ $other_expense->id == $monthly_expense->other_expense_master_id ? 'selected' : '' }}>
			                                                {{ $other_expense->other_expense }}
			                                            </option>
			                                        @endforeach
													
													</select>
										  	 </div>
											<div class="col-lg-6 col-12">
													<label for="other_expenses_amount" class="form-label">Other Expenses Amount<span class="text-danger">*</span>:</label>
												<input type="number" class="form-control no-negative" id="other_expenses_amount" name="other_expenses_amount" placeholder="Other Expenses Amount" value="{{ $monthly_expense->other_expenses_amount }}">
											</div>
										</div>
										<div class="row mb-4">
											<div class="col-6">
												<div class="row">
												    <label class="mb-1">Pre-Approved<span class="text-danger">*</span>:</label>
												    <div class="col-lg-2 col-12 mb-lg-0 mb-3">
												        <input class="form-check-input" type="radio" name="pre_approved" id="pre_approved_yes" value="Yes" 
												            @if($monthly_expense->pre_approved == 'Yes') checked @endif>
												        <label class="form-check-label" for="pre_approved_yes">Yes</label>
												    </div>
												    <div class="col-lg-2 col-12 mb-lg-0 mb-3">
												        <input class="form-check-input" type="radio" name="pre_approved" id="pre_approved_no" value="No" 
												            @if($monthly_expense->pre_approved == 'No') checked @endif>
												        <label class="form-check-label" for="pre_approved_no">No</label>
												    </div>
												    <div class="col-lg-2 col-12 mb-lg-0 mb-3">
												        <input class="form-check-input" type="radio" name="pre_approved" id="pre_approved_na" value="N.A" 
												            @if($monthly_expense->pre_approved == 'N.A') checked @endif>
												        <label class="form-check-label" for="pre_approved_na">N.A.</label>
												    </div>
												</div>
											</div>
											<div class="col-6">
												<div class="row">
													<div class="d-flex justify-content-between">
														<div>
															<label class="mb-1">Approved Date<span class="text-danger">*</span>:</label>
														</div>
														<div>
															<label for="ApprovedDate">Date<span class="text-danger">*</span>:</label>
														</div>
													</div>
													<div class="col-lg-6 col-12 mb-lg-0 ">
														<!-- <div class="row"> -->
															<label class="form-check-label" for="inlineRadioOptionsApprovedDate1">Date</label>
															<input class="form-check-input me-2" type="radio" name="inlineRadioOptionsApprovedDate" id="" value="option1">
															<label class="form-check-label" for="inlineRadioOptionsApprovedDate2">N.A.</label>
														  	<input class="form-check-input" type="radio" name="inlineRadioOptionsApprovedDate" id="" name="" value="option2">
													  <!-- </div> -->
													</div>
													<!-- <div class="col-lg-4 col-12 mb-lg-0 mb-3">
														
													</div> -->
													<div class="col-lg-6 col-12 mb-lg-0">
														<input type="date" id="approved_date" name="approved_date" class="form-control" value="{{ $monthly_expense->approved_date }}"> 
													</div>
												</div>
											</div>
										</div>
										<div class="row mb-4">
											<div class="col-lg-6 col-12 mb-lg-0 mb-3">
												<label for="approved_by" class="form-label">Approved By<span class="text-danger">*</span>:</label>
												<select class="form-select" aria-label="Default select example" id="approved_by" name="approved_by">
												<option value="" disabled selected>Select Aproved By</option>
													@foreach($users as $user)
			                                            <option value="{{ $user->id }}" {{ $user->id == $monthly_expense->approved_by ? 'selected' : '' }}>
			                                                {{ $user->name }}
			                                            </option>
			                                        @endforeach
												</select>
											</div>
											<div class="col-6">
												<div class="row">
													<label class="mb-1">Upload of Approvals Documnets :</label>
													<div class="col-lg-3 col-12 mb-lg-0 mb-3">
														<label class="form-check-label" for="inlineRadioOptionsUploadOfApprovalsDocumentsUpload">Upload</label>
														<input class="form-check-input" type="radio" name="inlineRadioOptionsUploadOfApprovalsDocuments" id="inlineRadioOptionsUploadOfApprovalsDocuments" value="option1">
													</div>
													<div class="col-lg-2 col-12 mb-lg-0 mb-3">
														<label class="form-check-label" for="inlineRadioOptionsUploadOfApprovalsDocumentsNA">N.A.</label>
														<input class="form-check-input" type="radio" name="inlineRadioOptionsUploadOfApprovalsDocuments" id="inlineRadioOptionsUploadOfApprovalsDocuments" value="option2">
													</div>
													<div class="col-lg-7 col-12 mb-lg-0 mb-3">
														<input class="form-control" type="file" id="upload_of_approval_documents" name="upload_of_approval_documents">
													</div>
												</div>
											</div>
										</div>
										<div class="row mb-4">
											<div class="col-6">
												<label class="mb-1"  >View of Attendace <span class="text-danger">*</span></label><br>
												
												<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#viewOfAttendenceModal">View</button>
											</div>
											<div class="col-6">
												<label for="remarks">Remarks <span class="text-danger">*</span></label>
												<input type="text" class="form-control" id="remarks" name="remarks" value="{{ $monthly_expense->remarks }}">
											</div>
										</div>
										<div class="row mb-4">
											<div class="col-12">
												<input class="form-check-input me-2" type="checkbox" value="1" id="accept_policy" name="accept_policy" {{ $monthly_expense->accept_policy == 1 ? 'checked' : '' }} required>

												<label class="form-check-label" for="accept_policy">I hereby confirmed that I have verify the Expenses and found OK as per Travel/ Daily Allowance Policy.</label>
											</div>
										</div>

									
							      </div>
							      <div class="modal-footer">
							      	<a href="{{ route('monthly_expenses.index') }}" class="btn btn-danger mb-5 me-2">Close</a>
							        <button type="submit" class="btn btn-success mb-5 me-5">Submit</button>
							      </div>
							    </div>
							</div>
						</form>
					
				</div>
				

		 		


				
				
				
				
				
				  
			</div>

		</div>
		<!--*******************
			Main Content End
		 *****************-->
		 </div>
		
		<div class="modal" tabindex="-1" id="viewOfAttendenceModal">
					<div class="modal-dialog modal-dialog-centered modal-xl">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">View of Attendance</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">Client Name</th>
            <th scope="col">Check In Time</th>
            <th scope="col">Check Out Time</th>
            <th scope="col">Joint Purpose Details</th>
            <th scope="col">Duration</th>
            <th scope="col">Check In Address</th>
        </tr>
    </thead>
    <tbody>
        @if(!$attendances || $attendances->isEmpty())
            <tr>
                <td colspan="6" class="text-center">No attendance found.</td>
            </tr>
        @else
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->customer_name }}</td>
                    <td>{{ $attendance->check_in }}</td>
                    <td>{{ $attendance->check_out }}</td>
                    <td>{{ $attendance->joint_purpose_details ?? 'N/A' }}</td>
                    <td>{{ $attendance->duration ?? 'N/A' }}</td>
                    <td>{{ $attendance->check_in_address ?? 'N/A' }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
						</div>
					  </div>
					</div>
				  </div>
	</div>

	<!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Normal JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    
    <!-- DataTables JS -->
    <!--<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>-->


	<script type="text/javascript">

	document.addEventListener('DOMContentLoaded', function() {
	        const checkbox = document.getElementById('Agreement');
	        const submitButton = document.getElementById('submitButton');

	        // Add an event listener to toggle the submit button
	        checkbox.addEventListener('change', function() {
	            submitButton.disabled = !checkbox.checked;
	        });
	    });
	</script>
    

    <script type="text/javascript">
	    $(document).ready(function() {
	        // Function to reset all fields inside a specific modal
	        function resetFields() {
			    $('#one_way_two_way_multi_location').prop('disabled', false);
			    $('#from, #to, #departure_time, #arrival_time, #km_as_per_user, #fare_amount, #da_total, #postage, #mobile_internet, #print_stationery, #other_expenses_amount, #approved_date')
			        .prop('readonly', false);

			    $('#mode_of_expense_master_id, #other_expense_master_id, #approved_by, #upload_of_approval_documents').prop('disabled', false);
			    
			    // Enable radio buttons but do NOT disable the accept_policy checkbox
			    $('input[type="radio"]').prop('disabled', false);
			}


	        // Listen for changes inside each expense type dropdown
			$(document).on('change', '#expense_type_master_id', function() {
			    resetFields(); // Reset all fields before applying conditions

			    var selectedValue = $(this).val();
				var location_da = parseFloat("{{ $policySetting->location_da ?? 0 }}") || 0;
				
				var ex_location_da = parseFloat("{{ $policySetting->ex_location_da ?? 0 }}") || 0;
				
				var outstation_da = parseFloat("{{ $policySetting->outstation_da ?? 0 }}") || 0;
				
				var total_da = location_da + ex_location_da + outstation_da;

				

			    if (selectedValue == 4 || selectedValue == 5 || selectedValue == 6 || selectedValue == 9) {
			        // Disable all fields except accept_policy
			        $('#one_way_two_way_multi_location').prop('disabled', true).val('One Way');
			        $('#departure_time, #arrival_time, #km_as_per_user, #km_as_per_google_map, #fare_amount, #da_location, #da_ex_location, #da_outstation, #da_total, #postage, #mobile_internet, #print_stationery, #other_expenses_amount')
			            .prop('readonly', true).val('0');
			        $('#from, #to').prop('readonly', true);
			        
			        $('#mode_of_expense_master_id, #other_expense_master_id, #approved_by, #approved_date, #upload_of_approval_documents').prop('disabled', true);
			        
			        $('input[type="radio"]').prop('disabled', true);

			        // Ensure accept_policy remains enabled
			        $('#accept_policy').prop('readonly', false);
			    } else if (selectedValue == 1) {
			        $('#da_ex_location, #da_outstation').prop('readonly', true).val('0');
			        $('#da_location').prop('readonly', true).val(location_da);
			        $('#da_total').prop('readonly', true).val(location_da);
			        
			    } else if (selectedValue == 2) {
			        $('#da_location, #da_outstation').prop('readonly', true).val('0');
			        $('#da_ex_location').prop('readonly', true).val(ex_location_da);
			        $('#da_total').prop('readonly', true).val(ex_location_da);
			        
			    } else if (selectedValue == 3) {
			        $('#da_location, #da_ex_location').prop('readonly', true).val('0');
			        $('#da_outstation').prop('readonly', true).val(outstation_da);
			        $('#da_total').prop('readonly', true).val(outstation_da);

			    }
			});

			// Trigger change event on page load if a value is already selected
			$('#expense_type_master_id').each(function() {
			    $(this).trigger('change');
			});

			// Listen for changes inside each mode of expense dropdown
			$(document).on('change', '#mode_of_expense_master_id', function() {
			    // resetFields(); // Reset all fields before applying conditions

			    var selectedValue = $(this).val();
			    
			    // Retrieve the policy charges from the server-side variable
			    var two_wheelers_charges = "{{ $policySetting->two_wheelers_charges ?? '' }}";        
			    var four_wheelers_charges = "{{ $policySetting->four_wheelers_charges ?? '' }}";

			    var db_fare_amount = "{{ $monthly_expense->fare_amount ?? '' }}";
			    var db_mode_of_expense = "{{ $monthly_expense->mode_of_expense_master_id ?? '' }}";
			    
			    // Get the km_as_per_user value from the input field
			    var km_as_per_user = $('#km_as_per_user').val();

			    // Check if km_as_per_user is a valid number
			    if (!km_as_per_user || isNaN(km_as_per_user)) {
			        km_as_per_user = 0; // Default to 0 if invalid or empty
			    }

			    // Calculate fare amounts based on selected vehicle type
			    var fare_of_two_wheelers = two_wheelers_charges * km_as_per_user;
			    var fare_of_four_wheelers = four_wheelers_charges * km_as_per_user;

			    // Apply logic based on selected value
			    if (selectedValue == 6) {
			        $('#fare_amount').prop('readonly', true).val(fare_of_two_wheelers); 
			    } else if (selectedValue == 5) {
			        $('#fare_amount').prop('readonly', true).val(fare_of_four_wheelers); 
			   
			    } else if (selectedValue == db_mode_of_expense) {
			        $('#fare_amount').prop('readonly', false).val(db_fare_amount); 
			    } else {
			        $('#fare_amount').prop('readonly', false).val('');
			    }
			});

			// Trigger change event on page load if a value is already selected
			$('#mode_of_expense_master_id').each(function() {
			    $(this).trigger('change');
			});

			// Listen for changes inside the km_as_per_user input field
			$(document).on('input', '#km_as_per_user', function() {
			    var selectedValue = $('#mode_of_expense_master_id').val();
			    
			    // Retrieve the policy charges from the server-side variable
			    var two_wheelers_charges = "{{ $policySetting->two_wheelers_charges ?? '' }}";        
			    var four_wheelers_charges = "{{ $policySetting->four_wheelers_charges ?? '' }}";
			    
			    // Get the updated km_as_per_user value
			    var km_as_per_user = $(this).val();

			    // Check if km_as_per_user is a valid number
			    if (!km_as_per_user || isNaN(km_as_per_user)) {
			        km_as_per_user = 0; // Default to 0 if invalid or empty
			    }

			    // Calculate fare amounts based on selected vehicle type
			    var fare_of_two_wheelers = two_wheelers_charges * km_as_per_user;
			    var fare_of_four_wheelers = four_wheelers_charges * km_as_per_user;
			    

			    // Apply logic based on selected value
			    if (selectedValue == 6) {
			        $('#fare_amount').prop('readonly', true).val(fare_of_two_wheelers); 
			    } else if (selectedValue == 5) {
			        $('#fare_amount').prop('readonly', true).val(fare_of_four_wheelers); 
			    } else {
			        $('#fare_amount').prop('readonly', false).val('');
			    }
			});


	    });
	</script>

	<script type="text/javascript">
		function initAutocomplete() {
		    let fromInput = document.getElementById("from");
		    let toInput = document.getElementById("to");
		    
		    let km_asPerGoogleInput = document.getElementById("km_as_per_google_map");
		    let multiLocationInput = document.getElementById("multi_location");
		    let returnInput = document.getElementById("return_input");
		    let dropdown = document.getElementById("one_way_two_way_multi_location");
		    let returnInputWrapper = document.getElementById("return_input_wrapper");

		    let options = { 
		        types: ["geocode"], 
		        fields: ["name"], 
		        componentRestrictions: { country: "IN" } // Restrict to India 🇮🇳
		    };

		    let fromAutocomplete = new google.maps.places.Autocomplete(fromInput, options);
		    let toAutocomplete = new google.maps.places.Autocomplete(toInput, options);
		    let multiLocationAutocomplete = new google.maps.places.Autocomplete(multiLocationInput, options);

		    let returnAutocomplete = new google.maps.places.Autocomplete(returnInput, options);
		    let fromSelected = false; // Track if a valid place is selected
		    let toSelected = false;

	            // Detect when a place is selected in "From" field
	        // fromAutocomplete.addListener("place_changed", function () {
	        // 	let place = fromAutocomplete.getPlace();
	        //     if (place.name) {
	        //             fromSelected = true; // Mark as valid selection
	        //     }
	        // });
	        // Initially disable return input and set tooltip
            returnInput.disabled = true;
            returnInput.title = "Enter 'From' location first.";
    
            fromAutocomplete.addListener("place_changed", function () {
                let place = fromAutocomplete.getPlace();
                if (place.name) {
                    fromSelected = true;
                    if (dropdown.value === "Two Way") {
                        returnInput.disabled = false;
                        returnInput.title = ""; // Remove tooltip when enabled
                    }
                }
            });
	            // Clear "From" field if user didn't select a place from the dropdown
	        fromInput.addEventListener("blur", function () {
	            if (!fromSelected) {
	            fromInput.value = ""; // Reset the field
	            }
	            fromSelected = false; // Reset flag for next input
	        });

	        toAutocomplete.addListener("place_changed", function () {
	        	let place = toAutocomplete.getPlace();
	            if (place.name) {
	                    toSelected = true; // Mark as valid selection
	            }
	        });
	            // Clear "From" field if user didn't select a place from the dropdown
	        // toInput.addEventListener("blur", function () {
	        //     if (!toSelected) {
	        //     toInput.value = ""; // Reset the field
	        //     }
	        //     toSelected = false; // Reset flag for next input
	        // });

		    // Show/hide multi-location input when "Multi Location" is selected
		    document.getElementById("one_way_two_way_multi_location").addEventListener("change", function () {
		        let multiLocationWrapper = document.getElementById("multi_location_wrapper");
		        if (this.value === "Multi Location") {
		        	// $('#to').prop('readonly', true).val('');
		            multiLocationWrapper.style.display = "block";
		            toInput.addEventListener("keydown", preventTyping);
	                toInput.addEventListener("keypress", preventTyping);
	                toInput.addEventListener("paste", preventTyping);
	                toInput.value = "";
	                // km_asPerGoogleInput.value = "";
		        } else {
		            multiLocationWrapper.style.display = "none";
		            toInput.value = "";
		            // toInput.style.pointerEvents = "none";
		            multiLocationInput.value = ""; // Reset additional locations

		            // Remove event listeners
				    toInput.removeEventListener("keydown", preventTyping);
				    toInput.removeEventListener("keypress", preventTyping);
				    toInput.removeEventListener("paste", preventTyping);
		        }
		        
		        // old code
		        // if (this.value === "Two Way") {
	            //         returnInputWrapper.style.display = "block";
	            //         returnInput.readOnly = false;
	            //         returnInput.value = "";
	            //         toInput.value = "";
	            //     } else {
	            //         returnInputWrapper.style.display = "none";
	            //         returnInput.value = "";
	            //         returnInput.readOnly = false;
	            //     }

		        // new code
	            if (this.value === "Two Way") {
                    returnInputWrapper.style.display = "block";
                    toInput.style.caretColor = "transparent";
                    toInput.addEventListener("keydown", preventTyping);
                    toInput.addEventListener("keypress", preventTyping);
                    toInput.addEventListener("paste", preventTyping);

                    if (fromInput.value.trim() === "") {
                        returnInput.disabled = true;
                        returnInput.title = "Enter 'From' location first.";
                    } else {
                        returnInput.disabled = false;
                        returnInput.title = ""; // Remove tooltip
                    }
                } else {
                    returnInputWrapper.style.display = "none";
                    returnInput.value = "";
                    returnInput.disabled = true;
                    returnInput.title = "Enter 'From' location first."; // Reset tooltip
                }

		    });
		    function preventTyping(event) {
	                event.preventDefault();
	            }

		    // Remove "India" from place names in the "To" input
		    function formatLocationName(locationName) {
		        return locationName.replace(/,?\s*India$/, '').trim();
		    }
		    
		    returnAutocomplete.addListener("place_changed", function () {
	            let place = returnAutocomplete.getPlace();
	            if (!place.name) return;
	        
	            let fromValue = formatLocationName(fromInput.value.trim()); 
	            let returnValue = formatLocationName(place.name.trim());
	        
	            // Avoid duplicate data
	            let formattedLocation = returnValue.includes(fromValue) 
	                ? `"${fromValue}"` 
	                : `"${returnValue}", "${fromValue}"`;
	        
	            // Assign the formatted location to toInput
	            toInput.value = formattedLocation;
	        
	            returnInput.readOnly = true;
	            
	            // **Manually trigger the blur event**
	            returnInput.dispatchEvent(new Event('blur'));
	        });




		    // Append selected locations to "To" input
		    multiLocationAutocomplete.addListener("place_changed", function () {
			    let place = multiLocationAutocomplete.getPlace();
			    if (!place.name) return;

			    // Format the place name by removing "India"
			    let placeName = formatLocationName(place.name);

			    // Wrap the new location in double quotes
			    let formattedPlaceName = `"${placeName}"`;

			    // Append the new location to the "To" input field, adding a comma if there is already content
			    if (toInput.value.trim()) {
			        toInput.value += `,${formattedPlaceName}`;
			    } else {
			        toInput.value = formattedPlaceName;
			    }

			    multiLocationInput.value = ""; // Clear input field after adding
			});


		    // Ensure "From" input is also formatted when the user selects a location
		    fromAutocomplete.addListener("place_changed", function () {
		        let place = fromAutocomplete.getPlace();
		        if (!place.name) return;

		        // Format the "From" location by removing "India"
		        let placeName = formatLocationName(place.name);

		        // Set the formatted location in the "From" input
		        fromInput.value = placeName;
		    });

		    // Ensure "To" input is formatted when the user selects a location
		    toAutocomplete.addListener("place_changed", function () {
		        let place = toAutocomplete.getPlace();
		        if (!place.name) return;

		        // Format the "To" location by removing "India"
		        let placeName = formatLocationName(place.name);

		        // Set the formatted location in the "To" input
		        toInput.value = `"${placeName}"`;
		    });
		}

	window.onload = initAutocomplete;


	</script>
	<script type="text/javascript">
	async function fetchDistanceMatrixOnClick() {
	    const from = document.getElementById('from').value.trim();
	    const to = document.getElementById('to').value.trim();

	    // if (!from || !to) {
	    //     alert('Please enter both "From" and "To" locations.');
	    //     return;
	    // }

	    // Parse "To" input which may have locations wrapped in double quotes
	    const toLocations = parseLocations(to);


	    console.log("to input", toLocations);

	    // Start the journey from the "From" location
	    const locations = [from, ...toLocations];

	    let totalDistance = 0;

	    try {
	        console.log('Sending request to google server...');
	        for (let i = 0; i < locations.length - 1; i++) {
	            const fromLocation = locations[i];
	            const toLocation = locations[i + 1];

	            // Fetch the distance between each consecutive pair of locations
	            const response = await fetch('/get-distance', {
	                method: 'POST',
	                headers: {
	                    'Content-Type': 'application/json',
	                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
	                },
	                body: JSON.stringify({ from: fromLocation, to: toLocation }),
	            });

	            const data = await response.json();
	            console.log('Server response:', data);

	            if (response.ok) {
	                const distance = data.rows?.[0]?.elements?.[0]?.distance;

	                if (distance && distance.value) {
	                    totalDistance += distance.value; // Add distance in meters
	                } else {
	                    console.log(`Unable to retrieve distance from ${fromLocation} to ${toLocation}.`);
	                    document.getElementById('km_as_per_google_map').value = '0';
	                    return;
	                }
	            } else {
	                alert('Error fetching data: ' + (data.error || 'Unknown Error'));
	                document.getElementById('km_as_per_google_map').value = '0';
	                return;
	            }
	        }

	        // Convert total distance from meters to kilometers
	        const totalDistanceInKm = (totalDistance / 1000).toFixed(2);

	        // Fill the total distance in the km_as_per_google_map input field
	        document.getElementById('km_as_per_google_map').value = totalDistanceInKm;

	    } catch (error) {
	        console.error('Error fetching distance matrix:', error);
	        alert('An error occurred while fetching the distance.');
	    }
	}

	// Function to parse the locations (handles both single and multiple locations with or without quotes)
	function parseLocations(input) {
	    // Match locations that are either wrapped in double quotes or separated by commas
	    const regex = /"([^"]+)"|([^",]+)(?=\s*,|\s*$)/g;

	    let matches, locations = [];

	    // Use regex to find all locations, either wrapped in quotes or plain
	    while ((matches = regex.exec(input)) !== null) {
	        // Add the location to the array (either quoted or plain)
	        if (matches[1]) {
	            locations.push(matches[1]); // For quoted locations
	        } else if (matches[2]) {
	            locations.push(matches[2]); // For unquoted locations
	        }
	    }

	    // Clean up leading/trailing spaces and return the list of locations
	    return locations.map(location => location.trim());
	}

	// Attach the event listener to a button or another appropriate element
	// document.getElementById('km_as_per_google_map').addEventListener('click', fetchDistanceMatrixOnClick);

	</script>
	<script type="text/javascript">
		
		// Add event listener to the km_as_per_google_map input field
	// document.getElementById('km_as_per_google_map').addEventListener('click', fetchDistanceMatrixOnClick);

		// to trigger when both feild is filled
	document.addEventListener('DOMContentLoaded', function () {
	    let toInput = document.getElementById('to'); // "To" input
	    let multi_locationInput = document.getElementById('multi_location');
	    let fromInput = document.getElementById('from'); // "From" input
	    let returnInput = document.getElementById("return_input");

	    function checkAndTriggerDistanceCalculation() {
	        if (fromInput.value.trim() !== "" && toInput.value.trim() !== "") {
	            console.log('Both "From" and "To" fields are filled. Calculating distance...');
	            // Wait for 2 seconds (2000 milliseconds) before triggering the function
	            setTimeout(() => {
	                console.log('Calculating distance now...');
	                fetchDistanceMatrixOnClick();
	            }, 2000); // 2000 milliseconds = 2 seconds
	        }
	    }

	    // Listen for input in "From" field
	    fromInput.addEventListener('input', checkAndTriggerDistanceCalculation);

	    // Listen for input in "To" field
	    toInput.addEventListener('input', checkAndTriggerDistanceCalculation);
	    toInput.addEventListener('blur', checkAndTriggerDistanceCalculation);
	    // Check if returnInput is already disabled and trigger the function
        // if (returnInput.disabled) {
        //     checkAndTriggerDistanceCalculation();
        // }
        

	    // Ensure it also triggers on "To" field blur (when user finishes typing)
	    multi_locationInput.addEventListener('input', checkAndTriggerDistanceCalculation);	    
	    multi_locationInput.addEventListener('blur', checkAndTriggerDistanceCalculation);
	    
	    returnInput.addEventListener('input', checkAndTriggerDistanceCalculation);
	    returnInput.addEventListener('blur', checkAndTriggerDistanceCalculation);

	    
	});

	// to trigger when to input is filled and de slected
	// document.addEventListener('DOMContentLoaded', function () {
	//     let toInput = document.getElementById('to'); // "To" input
	//     let fromInput = document.getElementById('from'); // "From" input

	//     toInput.addEventListener('focus', function () {
	//         console.log('User started typing in "To" field...');
	//     });

	//     toInput.addEventListener('blur', function () {
	//         if (fromInput.value.trim() !== "" && toInput.value.trim() !== "") {
	//             console.log('User finished typing in "To" field. Calculating distance...');
	//             fetchDistanceMatrixOnClick();
	//         } else {
	//             console.warn('Both "From" and "To" must be filled before calculating distance.');
	//         }
	//     });
	// });
	// document.addEventListener('DOMContentLoaded', function () {
	//     let toInput = document.getElementById('to'); // "To" input
	//     let fromInput = document.getElementById('from'); // "From" input

	//     toInput.addEventListener('focus', function () {
	//         console.log('User started typing in "To" field...');
	//     });

	//     toInput.addEventListener('blur', function () {
	//         if (fromInput.value.trim() !== "" && toInput.value.trim() !== "") {
	//             console.log('User finished typing in "To" field. Waiting for 3 seconds before calculating distance...');

	//             // Wait for 2 seconds (2000 milliseconds) before triggering the function
	//             setTimeout(() => {
	//                 console.log('Calculating distance now...');
	//                 fetchDistanceMatrixOnClick();
	//             }, 3000); // 2000 milliseconds = 2 seconds
	//         } else {
	//             console.warn('Both "From" and "To" must be filled before calculating distance.');
	//         }
	//     });
	// });
	</script>
	 <script type="text/javascript">
	 	$(document).on('input mouseover', '#to', function() {
		    $(this).attr('title', $(this).val());
		})
	 </script>
	 
	 <script>
      document.querySelectorAll('.no-negative').forEach(input => {
        input.addEventListener('input', function () {
          if (this.value < 0) {
            this.value = 0;
          }
        });
      });
    </script>
                        


		
</body>
</html>