
				
				  
				  	<div class="modal" tabindex="-1" id="editModal{{ $monthly_expense->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $monthly_expense->id }}" aria-hidden="true">
				  	<form action="{{ route('monthly_expenses.update', $monthly_expense->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                    <div class="modal-dialog modal-lg modal-dialog-centered">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title">Add Expenses</h5>
				        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
				      </div>
				      <div class="modal-body">
				        
                        <div class="card">
                        	<div class="card-header" style="background-color:#077fad; color:white;">
							    Expenses
							  </div>
							  <div class="card-body">
								  <div class="row mb-4">
								   
								   <div class="col-lg-6 col-12">
								   	 <label for="expense_date" class="form-label">Expense Date<span class="text-danger">*</span>:</label>
									 <input type="date" class="form-control" id="expense_date{{ $monthly_expense->id }}" name="expense_date{{ $monthly_expense->id }}" placeholder="Expense Date" value="{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('Y-m-d') }}">
								   </div>
								   <div class="col-lg-6 col-12">
								   	 <label for="expense_type_master_id" class="form-label">Expense Type<span class="text-danger">*</span>:</label>
									 <select class="form-select" aria-label="Default select example" id="expense_type_master_id{{ $monthly_expense->id }}" name="expense_type_master_id{{ $monthly_expense->id }}">
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
										<select class="form-select" aria-label="Default select example" id="one_way_two_way_multi_location{{ $monthly_expense->id }}" name="one_way_two_way_multi_location{{ $monthly_expense->id }}">
										<option value="One Way" {{ $monthly_expense->one_way_two_way_multi_location === 'One Way' ? 'selected' : '' }}>One Way</option>
									    <option value="Two Way" {{ $monthly_expense->one_way_two_way_multi_location === 'Two Way' ? 'selected' : '' }}>Two Way</option>
									    <option value="Multi Location" {{ $monthly_expense->one_way_two_way_multi_location === 'Multi Location' ? 'selected' : '' }}>Multi Location</option>
										</select>
									</div>
									<div class="col-lg-6 col-12 mb-lg-0 mb-3">
										<label for="from" class="form-label">From<span class="text-danger">*</span>:</label>
										<input type="text" class="form-control" id="from{{ $monthly_expense->id }}" name="from{{ $monthly_expense->id }}" placeholder="From" value="{{ $monthly_expense->from }}">
									</div>
								   </div>
								   <div class="row mb-4">
									<div class="col-lg-6 col-12 mb-lg-0 mb-3">
										<label for="to" class="form-label">To<span class="text-danger">*</span>:</label>
										<input type="text" class="form-control" id="to{{ $monthly_expense->id }}" name="to{{ $monthly_expense->id }}" placeholder="To" value="{{ $monthly_expense->to }}">
									</div>
									<!-- Multi-location field (hidden by default) -->
									
							        <div id="multi_location_wrapper{{ $monthly_expense->id }}" style="display: none; margin-top: 10px;">
							            <label for="multi_location" class="form-label">Additional Locations:</label>
							            <input type="text" class="form-control" id="multi_location{{ $monthly_expense->id }}" placeholder="Type a location...">
							        </div>
									<div class="col-lg-6 col-12 mb-lg-0 mb-3">
										<label for="departure_time" class="form-label">Dep. Time:</label>
										<input type="datetime-local" class="form-control" id="departure_time{{ $monthly_expense->id }}" name="departure_time{{ $monthly_expense->id }}" placeholder="Dep. Time" value="{{ $monthly_expense->departure_time }}">
									</div>
								  </div>
								  <div class="row mb-4">
									<div class="col-lg-6 col-12 mb-lg-0 mb-3">
										<label for="arrival_time" class="form-label">Arr. Time:</label>
										<input type="datetime-local" class="form-control" id="arrival_time{{ $monthly_expense->id }}" name="arrival_time{{ $monthly_expense->id }}" placeholder="Arr. Time" value="{{ $monthly_expense->arrival_time }}">
									</div>
									<div class="col-lg-6 col-12 mb-lg-0 mb-3">
										<label for="km_as_per_user" class="form-label">KM. as per User<span class="text-danger">*</span></label>
										<input type="number" class="form-control" id="km_as_per_user{{ $monthly_expense->id }}" name="km_as_per_user{{ $monthly_expense->id }}" placeholder="KM. as per User" value="{{ $monthly_expense->km_as_per_user }}">
									</div>
								  </div>
								  <div class="row mb-4">
									<div class="col-lg-6 col-12 mb-lg-0 mb-3">
										<label for="km_as_per_google_map" class="form-label">KM. as per Google Map:<span class="text-danger">*</span></label>
										<input type="number" class="form-control" id="km_as_per_google_map{{ $monthly_expense->id }}" name="km_as_per_google_map{{ $monthly_expense->id }}" placeholder="KM. as per Google Map" value="{{ $monthly_expense->km_as_per_google_map }}" readonly>
									</div>
									<div class="col-lg-6 col-12 mb-lg-0 mb-3">
										<label for="mode_of_expense_master_id" class="form-label">Expense Mode:<span class="text-danger">*</span></label>
										<select class="form-select" aria-label="Default select example" id="mode_of_expense_master_id{{ $monthly_expense->id }}" name="mode_of_expense_master_id{{ $monthly_expense->id }}">
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
										<input type="number" class="form-control" id="fare_amount{{ $monthly_expense->id }}" name="fare_amount{{ $monthly_expense->id }}" placeholder="Fare Amount" value="{{ $monthly_expense->fare_amount }}">
									</div>
									<div class="col-lg-6 col-12 mb-lg-0 mb-3">
										<label for="da_location" class="form-label">DA(Location):</label>
										<input type="number" class="form-control" id="da_location{{ $monthly_expense->id }}" name="da_location{{ $monthly_expense->id }}" placeholder="DA(Location)" value="{{ $monthly_expense->da_location }}">
									</div>
								  </div>
								</div>
						  </div>



						  <hr>
						  <div class="card">
                        	<div class="card-header" style="background-color:#077fad; color:white;">
							    Monthly Expenses
							  </div>
							  <div class="card-body">
								  <div class="row mb-4">
									 <div class="col-lg-6 col-12 mb-lg-0 mb-3">
										  <label for="da_ex_location" class="form-label">DA(Ex-Location):</label>
									   <input type="number" class="form-control" id="da_ex_location{{ $monthly_expense->id }}" name="da_ex_location{{ $monthly_expense->id }}" placeholder="DA(Ex-Location)" value="{{ $monthly_expense->da_ex_location }}">
									 </div>
									 <div class="col-lg-6 col-12">
										  <label for="da_outstation" class="form-label">DA(Outstation)<span class="text-danger">*</span>:</label>
									   <input type="number" class="form-control" id="da_outstation{{ $monthly_expense->id }}" name="da_outstation{{ $monthly_expense->id }}" placeholder="DA(Outstation)" value="{{ $monthly_expense->da_outstation }}">
									 </div>
									</div>
									<div class="row mb-4">
										<div class="col-lg-6 col-12 mb-lg-0 mb-3">
											<label for="da_total" class="form-label">DA(Total)<span class="text-danger">*</span>:</label>
										 <input type="number" class="form-control" id="da_total{{ $monthly_expense->id }}" name="da_total{{ $monthly_expense->id }}" placeholder="DA(Total)" value="{{ $monthly_expense->da_total }}">
									  	 </div>
										<div class="col-lg-6 col-12">
												<label for="postage" class="form-label">Postage<span class="text-danger">*</span>:</label>
											<input type="number" class="form-control" id="postage{{ $monthly_expense->id }}" name="postage" placeholder="Postage{{ $monthly_expense->id }}" value="{{ $monthly_expense->postage }}">
										</div>
									 </div>
									 <div class="row mb-4">
										<div class="col-lg-6 col-12 mb-lg-0 mb-3">
											<label for="mobile_internet" class="form-label">Mobile/Internet<span class="text-danger">*</span>:</label>
										 <input type="number" class="form-control" id="mobile_internet{{ $monthly_expense->id }}" name="mobile_internet{{ $monthly_expense->id }}" placeholder="Mobile/Internet" value="{{ $monthly_expense->mobile_internet }}">
									  	 </div>
										<div class="col-lg-6 col-12">
												<label for="print_stationery" class="form-label">Print Stationery<span class="text-danger">*</span>:</label>
											<input type="number" class="form-control" id="print_stationery{{ $monthly_expense->id }}" name="print_stationery{{ $monthly_expense->id }}" placeholder="Print Stationery" value="{{ $monthly_expense->print_stationery }}">
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
											<input type="number" class="form-control" id="other_expenses_amount{{ $monthly_expense->id }}" name="other_expenses_amount{{ $monthly_expense->id }}" placeholder="Other Expenses Amount" value="{{ $monthly_expense->other_expenses_amount }}">
										</div>
									</div>
									<div class="row mb-4">
										<div class="col-6">
											<div class="row">
											    <label class="mb-1">Pre-Approved<span class="text-danger">*</span>:</label>
											    <div class="col-lg-2 col-12 mb-lg-0 mb-3">
											        <input class="form-check-input" type="radio" name="pre_approved{{ $monthly_expense->id }}" id="pre_approved_yes{{ $monthly_expense->id }}" value="Yes" 
											            @if($monthly_expense->pre_approved == 'Yes') checked @endif>
											        <label class="form-check-label" for="pre_approved_yes">Yes</label>
											    </div>
											    <div class="col-lg-2 col-12 mb-lg-0 mb-3">
											        <input class="form-check-input" type="radio" name="pre_approved{{ $monthly_expense->id }}" id="pre_approved_no{{ $monthly_expense->id }}" value="No" 
											            @if($monthly_expense->pre_approved == 'No') checked @endif>
											        <label class="form-check-label" for="pre_approved_no">No</label>
											    </div>
											    <div class="col-lg-2 col-12 mb-lg-0 mb-3">
											        <input class="form-check-input" type="radio" name="pre_approved{{ $monthly_expense->id }}" id="pre_approved_na{{ $monthly_expense->id }}" value="N.A" 
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
														<input class="form-check-input me-2" type="radio" name="inlineRadioOptionsApprovedDate{{ $monthly_expense->id }}" id="" value="option1">
														<label class="form-check-label" for="inlineRadioOptionsApprovedDate2">N.A.</label>
													  	<input class="form-check-input" type="radio" name="inlineRadioOptionsApprovedDate{{ $monthly_expense->id }}" id="" name="" value="option2">
												  <!-- </div> -->
												</div>
												<!-- <div class="col-lg-4 col-12 mb-lg-0 mb-3">
													
												</div> -->
												<div class="col-lg-6 col-12 mb-lg-0">
													<input type="date" id="approved_date{{ $monthly_expense->id }}" name="approved_date{{ $monthly_expense->id }}" class="form-control" value="{{ $monthly_expense->approved_date }}"> 
												</div>
											</div>
										</div>
									</div>
									<div class="row mb-4">
										<div class="col-lg-6 col-12 mb-lg-0 mb-3">
											<label for="approved_by" class="form-label">Approved By<span class="text-danger">*</span>:</label>
											<select class="form-select" aria-label="Default select example" id="approved_by{{ $monthly_expense->id }}" name="approved_by{{ $monthly_expense->id }}">
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
													<input class="form-check-input" type="radio" name="inlineRadioOptionsUploadOfApprovalsDocuments{{ $monthly_expense->id }}" id="inlineRadioOptionsUploadOfApprovalsDocuments{{ $monthly_expense->id }}" value="option1">
												</div>
												<div class="col-lg-2 col-12 mb-lg-0 mb-3">
													<label class="form-check-label" for="inlineRadioOptionsUploadOfApprovalsDocumentsNA">N.A.</label>
													<input class="form-check-input" type="radio" name="inlineRadioOptionsUploadOfApprovalsDocuments{{ $monthly_expense->id }}" id="inlineRadioOptionsUploadOfApprovalsDocuments{{ $monthly_expense->id }}" value="option2">
												</div>
												<div class="col-lg-7 col-12 mb-lg-0 mb-3">
													<input class="form-control" type="file" id="upload_of_approval_documents{{ $monthly_expense->id }}" name="upload_of_approval_documents{{ $monthly_expense->id }}">
												</div>
											</div>
										</div>
									</div>
									<div class="row mb-4">
										<div class="col-6">
											<label class="mb-1"  >View of Attendace <span class="text-danger">*</span></label><br>
											<button data-bs-toggle="modal" data-bs-target="#viewOfAttendenceModal" type="button" class="btn btn-success">View</button>
										</div>
										<div class="col-6">
											<label for="remarks">Remarks <span class="text-danger">*</span></label>
											<input type="text" class="form-control" id="remarks{{ $monthly_expense->id }}" name="remarks{{ $monthly_expense->id }}" value="{{ $monthly_expense->remarks }}">
										</div>
									</div>
									<div class="row mb-4">
										<div class="col-12">
											<input class="form-check-input me-2" type="checkbox" value="1" id="accept_policy{{ $monthly_expense->id }}" name="accept_policy{{ $monthly_expense->id }}" {{ $monthly_expense->accept_policy == 1 ? 'checked' : '' }}>

											<label class="form-check-label" for="accept_policy">I hereby confirmed that I have verify the Expenses and found OK as per Travel/ Daily Allowance Policy.</label>
										</div>
									</div>

								
						      </div>
						      <div class="modal-footer">
						        <button type="submit" class="btn btn-success">Submit</button>
						      </div>
						    </div>
						    </form>
						  </div>
						</div>
					 </div>

				
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
// 	$(document).ready(function() {
//     // Initialize Google Places API (Assuming you have the API key loaded)
//     let placesService = new google.maps.places.Autocomplete(
//         document.getElementById("multi_location"),
//         { types: ["geocode"] }
//     );

//     let selectedLocations = [];

//     // Handle selection from Places API
//     google.maps.event.addListener(placesService, 'place_changed', function() {
//         let place = placesService.getPlace();
//         if (place.formatted_address) {
//             selectedLocations.push(place.formatted_address);
//             updateLocationList();
//         }
//         $("#multi_location").val(""); // Clear input after adding
//     });

//     // Update the Multi-Location list UI
//     function updateLocationList() {
//         let listContainer = $("#multi_location_list");
//         listContainer.empty();

//         selectedLocations.forEach((location, index) => {
//             listContainer.append(`
//                 <div class="location-tag">
//                     ${location} <span class="remove-location" data-index="${index}">&times;</span>
//                 </div>
//             `);
//         });
//     }

//     // Remove a location when clicking "X"
//     $(document).on("click", ".remove-location", function() {
//         let index = $(this).data("index");
//         selectedLocations.splice(index, 1);
//         updateLocationList();
//     });

//     // Handle select change for "One Way / Two Way / Multi Location"
//     $("#one_way_two_way_multi_location").on("change", function() {
//         let selectedValue = $(this).val();
//         if (selectedValue === "Multi Location") {
//             $("#to").hide().prop("disabled", true);
//             $("#multi_location_wrapper").show();
//             $("#multi_location").prop("disabled", false);
//         } else {
//             $("#to").show().prop("disabled", false);
//             $("#multi_location_wrapper").hide();
//             $("#multi_location").prop("disabled", true);
//             selectedLocations = []; // Clear locations when switching back
//             updateLocationList();
//         }
//     });
// });


$(document).ready(function() {
    // Function to reset all fields inside a specific modal
    function resetFields(modal) {
        modal.find('[id^="one_way_two_way_multi_location"]').prop('disabled', false);
        modal.find('[id^="from"], [id^="to"], [id^="departure_time"], [id^="arrival_time"], [id^="km_as_per_user"], [id^="km_as_per_google_map"], [id^="fare_amount"], [id^="da_location"], [id^="da_ex_location"], [id^="da_outstation"], [id^="da_total"], [id^="postage"], [id^="mobile_internet"], [id^="print_stationery"], [id^="other_expenses_amount"], [id^="approved_date"]')
            .prop('disabled', false);

        modal.find('[id^="mode_of_expense_master_id"], [id^="other_expense_master_id"], [id^="approved_by"], [id^="upload_of_approval_documents"]').prop('disabled', false);
        
        // Enable radio buttons but do NOT disable the accept_policy checkbox
        modal.find('input[type="radio"]').prop('disabled', false);
    }

    // Listen for changes inside each modal's expense type dropdown
    $(document).on('change', '[id^="expense_type_master_id"]', function() {
        var modal = $(this).closest('.modal'); // Get the parent modal
        resetFields(modal); // Reset all fields before applying conditions

        var selectedValue = $(this).val();
        var location_da = "{{ $policySetting->location_da ?? '' }}";
        var ex_location_da = "{{ $policySetting->ex_location_da ?? '' }}";
        var outstation_da = "{{ $policySetting->outstation_da ?? '' }}";

        if (selectedValue == 4 || selectedValue == 5 || selectedValue == 6) {
            // Disable all fields except accept_policy
            modal.find('[id^="one_way_two_way_multi_location"]').prop('disabled', true).val('One Way');
            modal.find('[id^="departure_time"], [id^="arrival_time"], [id^="km_as_per_user"], [id^="km_as_per_google_map"], [id^="fare_amount"], [id^="da_location"], [id^="da_ex_location"], [id^="da_outstation"], [id^="da_total"], [id^="postage"], [id^="mobile_internet"], [id^="print_stationery"], [id^="other_expenses_amount"]')
                .prop('disabled', true).val('0');
            modal.find('[id^="from"], [id^="to"]').prop('disabled', true);
            
            modal.find('[id^="mode_of_expense_master_id"], [id^="other_expense_master_id"], [id^="approved_by"], [id^="approved_date"], [id^="upload_of_approval_documents"]').prop('disabled', true);
            
            modal.find('input[type="radio"]').prop('disabled', true);

            // Ensure accept_policy remains enabled
            modal.find('[id^="accept_policy"]').prop('disabled', false);
        } else if (selectedValue == 1) {
            modal.find('[id^="da_ex_location"], [id^="da_outstation"]').prop('disabled', true).val('0');
            modal.find('[id^="da_location"]').prop('disabled', false).val(location_da); 
        } else if (selectedValue == 2) {
            modal.find('[id^="da_location"], [id^="da_outstation"]').prop('disabled', true).val('0');
             modal.find('[id^="da_ex_location"]').prop('disabled', false).val(ex_location_da); 
        } else if (selectedValue == 3) {
            modal.find('[id^="da_location"], [id^="da_ex_location"]').prop('disabled', true).val('0');
            modal.find('[id^="da_outstation"]').prop('disabled', false).val(outstation_da);
        }
    });

    // Trigger change event on page load if a value is already selected
    $('[id^="expense_type_master_id"]').each(function() {
        $(this).trigger('change');
    });






	// Listen for changes inside each modal's expense type dropdown
$(document).on('change', '[id^="mode_of_expense_master_id"]', function() {
    var modal = $(this).closest('.modal'); // Get the parent modal
    resetFields(modal); // Reset all fields before applying conditions

    var selectedValue = $(this).val();
    
    // Retrieve the policy charges from the server-side variable
    var two_wheelers_charges = "{{ $policySetting->two_wheelers_charges ?? '' }}";        
    var four_wheelers_charges = "{{ $policySetting->four_wheelers_charges ?? '' }}";
    
    // Get the km_as_per_user value from the input field inside the modal
    var km_as_per_user = modal.find('[id^="km_as_per_user"]').val();

    // Check if km_as_per_user is a valid number
    if (!km_as_per_user || isNaN(km_as_per_user)) {
        km_as_per_user = 0; // Default to 0 if invalid or empty
    }

    // Calculate fare amounts based on selected vehicle type
    var fare_of_two_wheelers = two_wheelers_charges * km_as_per_user;
    var fare_of_four_wheelers = four_wheelers_charges * km_as_per_user;

    // Apply logic based on selected value
    if (selectedValue == 6) {
        modal.find('[id^="fare_amount"]').prop('disabled', true).val(fare_of_two_wheelers); 
    } else if (selectedValue == 5) {
        modal.find('[id^="fare_amount"]').prop('disabled', true).val(fare_of_four_wheelers); 
    } else {
        modal.find('[id^="fare_amount"]').prop('disabled', false).val('');
    }
});

// Trigger change event on page load if a value is already selected
$('[id^="mode_of_expense_master_id"]').each(function() {
    $(this).trigger('change');
});

// Listen for changes inside the km_as_per_user input field
$(document).on('input', '[id^="km_as_per_user"]', function() {
    var modal = $(this).closest('.modal'); // Get the parent modal
    var selectedValue = modal.find('[id^="mode_of_expense_master_id"]').val();
    
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
        modal.find('[id^="fare_amount"]').prop('disabled', true).val(fare_of_two_wheelers); 
    } else if (selectedValue == 5) {
        modal.find('[id^="fare_amount"]').prop('disabled', true).val(fare_of_four_wheelers); 
    } else {
        modal.find('[id^="fare_amount"]').prop('disabled', false).val('');
    }
});

});






</script>

<script>
function initAutocomplete() {
    console.log("Initializing autocomplete...");

    document.querySelectorAll("[id^='from']").forEach(fromInput => {
        let expenseId = fromInput.id.replace("from", "");
        let toInput = document.getElementById(`to${expenseId}`);
        let multiLocationInput = document.getElementById(`multi_location${expenseId}`);
        let multiLocationWrapper = document.getElementById("multi_location_wrapper");
        let selectElement = document.getElementById(`one_way_two_way_multi_location${expenseId}`);

        console.log(`Processing expense ID: ${expenseId}`);

        if (!fromInput || !toInput || !multiLocationInput || !selectElement) {
            console.warn(`Missing input elements for expense ID: ${expenseId}`);
            return; // Exit if any element is missing
        }

        let options = {
            types: ["geocode"],
            fields: ["name"],
            componentRestrictions: { country: "IN" }
        };

        try {
            let fromAutocomplete = new google.maps.places.Autocomplete(fromInput, options);
            let toAutocomplete = new google.maps.places.Autocomplete(toInput, options);
            let multiLocationAutocomplete = new google.maps.places.Autocomplete(multiLocationInput, options);

            console.log(`Autocomplete initialized successfully for expense ID: ${expenseId}`);

            fromAutocomplete.addListener("place_changed", function () {
                let place = fromAutocomplete.getPlace();
                if (!place.name) {
                    console.error(`Error fetching "From" location for expense ID: ${expenseId}`);
                    return;
                }
                console.log(`"From" location selected: ${place.name} (Expense ID: ${expenseId})`);
            });

            toAutocomplete.addListener("place_changed", function () {
                let place = toAutocomplete.getPlace();
                if (!place.name) {
                    console.error(`Error fetching "To" location for expense ID: ${expenseId}`);
                    return;
                }
                console.log(`"To" location selected: ${place.name} (Expense ID: ${expenseId})`);
            });

            multiLocationAutocomplete.addListener("place_changed", function () {
                let place = multiLocationAutocomplete.getPlace();
                if (!place.name) {
                    console.error(`Error fetching Multi Location for expense ID: ${expenseId}`);
                    return;
                }

                console.log(`Multi Location selected: ${place.name} (Expense ID: ${expenseId})`);

                if (toInput.value.trim()) {
                    toInput.value += `, ${place.name}`;
                } else {
                    toInput.value = place.name;
                }

                multiLocationInput.value = ""; // Clear input field after adding
                console.log(`Multi Location updated successfully for expense ID: ${expenseId}`);
            });

            // Show/hide multi-location input when "Multi Location" is selected
            selectElement.addEventListener("change", function () {
                console.log(`Selection changed to: ${this.value} (Expense ID: ${expenseId})`);
                if (this.value === "Multi Location") {
                    multiLocationWrapper.style.display = "block";
                    console.log(`Multi Location input shown for expense ID: ${expenseId}`);
                } else {
                    multiLocationWrapper.style.display = "none";
                    multiLocationInput.value = ""; // Reset additional locations
                    console.log(`Multi Location input hidden for expense ID: ${expenseId}`);
                }
            });

        } catch (error) {
            console.error(`Error initializing autocomplete for expense ID: ${expenseId}`, error);
        }
    });
}

// Ensure Google Places API is loaded before running the script
window.addEventListener("load", function () {
    console.log("Checking Google Maps API...");
    if (typeof google !== "undefined" && google.maps && google.maps.places) {
        console.log("Google Maps API loaded successfully.");
        initAutocomplete();
    } else {
        console.error("Google Maps API failed to load.");
    }
});




</script>

