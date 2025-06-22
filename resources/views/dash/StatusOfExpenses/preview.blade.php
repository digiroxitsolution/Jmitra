<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Fontawsome Icon -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
	<!-- Bootstrap CSS -->
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

	<!-- Normal CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
	<!-- Favicon Icon -->
	<link rel="icon" href="{{ asset('assets/images/Favicon.png') }}">
	<title>@yield('title', config('app.name', 'Jmitra & Co. Pvt. Ltd'))</title>
	
	 

</head>
<body>
   <div id="pdf-content">
    
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
						  <h4 class="" style="height: 50px;">Preview of {{ \Carbon\Carbon::parse($month)->format('F') }} Expenses

						  	<button id="download-pdf" class="btn btn-primary">Download PDF</button>


						  </h4>

						  
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
											<td rowspan="2">Print Stationery</td>
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
										@foreach ($statement_of_expense as $key => $monthly_expense)
										<tr>
											<td>{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('d-m-Y') }}</td>
											<td>
												@if($monthly_expense->expense_type_master_id == 4 || $monthly_expense->expense_type_master_id == 5 || $monthly_expense->expense_type_master_id == 6 || $monthly_expense->expense_type_master_id == 9)

		                                            {{ $monthly_expense->ExpenseTypeMaster->expense_type  }}
		                                        @else
		                                            {{ $monthly_expense->from }}
		                                        @endif
											</td>
											<td>
												@if($monthly_expense->expense_type_master_id == 4 || $monthly_expense->expense_type_master_id == 5 || $monthly_expense->expense_type_master_id == 6 || $monthly_expense->expense_type_master_id == 9)

		                                            {{ $monthly_expense->ExpenseTypeMaster->expense_type  }}
		                                        @else
		                                            {{ $monthly_expense->to }}
		                                        @endif
											</td>
											
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
											<td>{{ $monthly_expense->print_stationery ?? 'N/A' }}</td>
											<td class="table-active">
														<a href="javascript:void(0);" 
														   data-url="{{ route('get_other_attendance', ['id' => $usser->id, 'attendance_date' => $monthly_expense->expense_date]) }}" 
														   class="view-attendance-btn">
														    <button type="button" class="btn btn-info float-end ms-2 text-white">
														        View
														    </button>
														</a>

													</td>
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
											<td>{{ $total_print_stationery }}</td>
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
											<td>{{ $total_fare_amount ?? '0' }}</td>
											
										</tr>
										<tr>
											<td>DA (Location) + DA (Ex-Location)</td>
											<td>{{ $total_Da ?? 'N/A' }}</td>
											
										</tr>
										<tr>
											<td>DA Outstation</td>
											<td>{{ $da_outstation ?? 'N/A' }}</td>
											
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


										
											<tr>
												<td class="text-primary">Advance Taken (If Any):</td>
												<td colspan="2">
													<input type="number" class="form-control" value="{{ $UserExpenseOtherRecords_filter->advance_taken }}" name="advance_taken" id="advance_taken" {{ $UserExpenseOtherRecords_filter->is_submitted === 1 ? 'disabled' : '' }} ></td>
											</tr>
											<tr>
												<td class="text-primary">Remark of Advance Taken</td>
												<td colspan="2">
													<input type="text" class="form-control" value="{{ $UserExpenseOtherRecords_filter->remark_of_advance_taken }}" id="remark_of_advance_taken" name="remark_of_advance_taken" {{ $UserExpenseOtherRecords_filter->is_submitted === 1 ? 'disabled' : '' }} >
												</td>
											</tr>
												

										


										<tr>
											<td class="text-primary">Balance Due to CO./EMP:</td>
											<td colspan="2"><input type="text" class="form-control" value="{{ $balance_dues }}" disabled></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row mb-4">
								<div class="form-check">
									
										<input class="form-check-input me-2 ms-1" type="checkbox" value="1" id="accept_policy" name="accept_policy" {{ $UserExpenseOtherRecords_filter->accept_policy == 1 ? 'checked' : '' }} {{ $UserExpenseOtherRecords_filter->is_submitted === 1 ? 'disabled' : '' }} >
										

									

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

	                                {{ $UserExpenseOtherRecords_filter->date_of_submission ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->date_of_submission)->format('d-m-Y') : 'N/A' }}
								    <br>
								    {{ $UserExpenseOtherRecords_filter->date_of_submission ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->date_of_submission)->format('h:i A') : 'N/A' }}

	                            </div>
								<div class="col-lg-4 col-12">Verified by<br>{{ $UserExpenseOtherRecords_filter->verifiedBy?->name ?? 'N/A' }}<br>

									{{ $UserExpenseOtherRecords_filter->verified_time ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->verified_time)->format('d-m-Y') : 'N/A' }}
								    <br>
								    {{ $UserExpenseOtherRecords_filter->verified_time ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->verified_time)->format('h:i A') : 'N/A' }}
								</div>
								<div class="col-lg-4 col-12">Approved by<br>
									{{ $UserExpenseOtherRecords_filter->approvedBy?->name ?? 'N/A' }}<br>

									{{ $UserExpenseOtherRecords_filter->approved_time ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->approved_time)->format('d-m-Y') : 'N/A' }}
								    <br>
								    {{ $UserExpenseOtherRecords_filter->approved_time ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->approved_time)->format('h:i A') : 'N/A' }}

								</div>
							</div>
						</div>
						<div class="card-footer" style="height: 50px;">

							

							

							<a href="{{ route('status_of_expenses')}}"><button type="button" class="btn btn-danger float-end ms-2" data-bs-dismiss="modal">Closed</button></a>
							
						</div>
					  
					</div>
				  </div>
				<!--*******************
					   Form Submit of Selected Month Start
				*****************-->
				
				

		 </div>
	</div>
</div>

		
		@include('dash.HODexpensePendingForVerification.viewAttendance')	
	
</div>
	<!-- Bootstrap JS -->
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
	<!-- Normal JS -->
	<script src="{{ asset('assets/js/script.js') }}"></script>

	<script type="text/javascript">

    document.addEventListener('DOMContentLoaded', function () {
    const attendanceModal = document.getElementById('viewOfAttendenceModal');
    const modalBodyTable = attendanceModal.querySelector('tbody');

    document.querySelectorAll('.view-attendance-btn').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.getAttribute('data-url');

            // Clear the modal content
            modalBodyTable.innerHTML = '';

            // Fetch the attendance data
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        modalBodyTable.innerHTML = `<tr><td colspan="7" class="text-center">${data.error}</td></tr>`;
                    } else {
                        data.forEach(attendance => {
                            const formatDateTime = (dateTimeString) => {
                                if (!dateTimeString) return 'N/A';

                                const date = new Date(dateTimeString);
                                if (isNaN(date.getTime())) return 'Invalid Date';

                                // Manually extract and format day, month, and year
                                const day = String(date.getDate()).padStart(2, '0');  // Ensure two digits
                                const month = String(date.getMonth() + 1).padStart(2, '0');  // Months are 0-based
                                const year = date.getFullYear();  // Get full 4-digit year

                                // Format time in 12-hour format with AM/PM
                                let hours = date.getHours();
                                const minutes = String(date.getMinutes()).padStart(2, '0');
                                const amPm = hours >= 12 ? 'PM' : 'AM';
                                hours = hours % 12 || 12;  // Convert 24-hour format to 12-hour

                                return `${day}-${month}-${year} ${hours}:${minutes} ${amPm}`;
                            };


                            const checkIn = formatDateTime(attendance.check_in);
                            const checkOut = formatDateTime(attendance.check_out);
                            const checkInCheckOut = checkIn !== 'N/A' && checkOut !== 'N/A' ? `${checkIn} - ${checkOut}` : 'N/A';

                            let duration = 'N/A';
                            if (checkIn !== 'N/A' && checkOut !== 'N/A') {
                                const checkInDate = new Date(attendance.check_in);
                                const checkOutDate = new Date(attendance.check_out);

                                // Calculate duration in milliseconds
                                const timeDifference = checkOutDate - checkInDate;

                                // Convert milliseconds to hours and minutes
                                if (timeDifference > 0) {
                                    const hours = Math.floor(timeDifference / (1000 * 60 * 60));
                                    const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                                    duration = `${hours} hr ${minutes} min`;
                                } else {
                                    duration = 'Invalid duration'; // If check-out is earlier than check-in
                                }
                            }

                            const row = `
                                <tr>
                                    <td>${attendance.customer_name || 'N/A'}</td>
                                    <td>${attendance.customer_type || 'N/A'}</td>
                                    <td>${checkIn}</td>
                                    <td>${checkOut}</td>
                                    <td>${attendance.joint_purpose_details || 'N/A'}</td>
                                    <td>${duration}</td>
                                    <td>${attendance.check_in_address || 'N/A'}</td>
                                </tr>`;
                            modalBodyTable.insertAdjacentHTML('beforeend', row);
                        });
                    }

                    // Show the modal
                    const modalInstance = new bootstrap.Modal(attendanceModal);
                    modalInstance.show();
                })
                .catch(error => {
                    modalBodyTable.innerHTML = `<tr><td colspan="7" class="text-center">Failed to fetch data. Try again.</td></tr>`;
                    console.error('Error fetching attendance data:', error);
                });
        });
    });
});

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
document.getElementById("download-pdf").addEventListener("click", function () {
    const element = document.getElementById("pdf-content");

    // Clone the original element to apply scale without affecting the UI
    const clone = element.cloneNode(true);
    clone.style.transform = "scale(0.6)";
    clone.style.transformOrigin = "top left";
    clone.style.width = "166.67%"; // 100 / 0.6 to maintain layout width
    clone.style.position = "absolute"; // Take it out of layout
    clone.style.left = "-9999px"; // Hide it off-screen

    document.body.appendChild(clone);

    const opt = {
        margin:       0.2,
        filename:     'Expense_Report.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  {
            scale: 2, // keep canvas quality high
            useCORS: true
        },
        jsPDF:        {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
        }
    };

    html2pdf().set(opt).from(clone).save().then(() => {
        document.body.removeChild(clone);
    });
});
</script>
		
</body>
</html>