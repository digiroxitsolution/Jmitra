@extends('layouts.main')
@section('title', 'Expenses Slip Report | Jmitra & Co. Pvt. Ltd')
@section('content')	
		<div class="container-fluid px-4">
			<div class="row my-5">
				<div class="col"> 
					<div class="border table-header p-4 position-relative rounded-top-4">
							<div class="d-flex justify-content-between">
								<h5 class="text-white">Expense Slip Report</h5>
								<form action="{{ route('expenses_slip_report_print') }}" method="POST">
									@csrf
								
										
										<input type="text" name="employee_idPass" id="employee_idPass" class="form-control mt-2" autocomplete="off" placeholder="Or Enter Employee Name" value="{{ $employee_idPass }}" hidden>
										
									
										
										<input type="month" name="MonthOfYearPass" id="MonthOfYearPass" class="form-control"  value="{{ $MonthOfYearPass }}" hidden>
									
										<button type="submit" class="btn btn-warning text-white">Print</button>
								
								
								</form>

							</div>
					</div>
 
					<div class="bg-white p-4 border">
						<div class="row mb-4">
							<div class="col-lg-4 col-12">
								<h5 class="text-muted">Your Company Name</h5>
							</div>
						</div>
						<form action="{{ route('search_expenses_slip_report') }}" method="POST">
							@csrf
						<div class="row mb-4">
							<div class="col-lg-5 col-12">
								<label for="">Employee ID or Employee Name</label>
								<input type="text" name="employee_id" id="employee_id" class="form-control mt-2" autocomplete="off" placeholder="Or Enter Employee Name">
								<div id="employee-suggestions" class="suggestions-list"></div>
							</div>
							<div class="col-lg-5 col-12">
								<label for="">Month of</label>
								<input type="month" name="MonthOfYear" id="MonthOfYear" class="form-control" required>
							</div>
							<div class="col-lg-2 col-12">
								<button type="submit" class="btn btn-primary">Search</button>
							</div>
						</div>
						
						
						</form>

					</div>
					
					<div class="bg-white p-4 border">					
						
						<div class="row mb-4">
							<div class="col-lg-6 col-12">
								<p>Expense ID: {{ $expense_id }}</p>
							</div>
							<div class="col-lg-6 col-12">
								<p>Expense Month: {{ $monthName }}</p>
							</div>
						</div>
						<div class="row mb-4">
							<div class="col-lg-6 col-12">
								<p>Employee Name: {{ $user->name }}</p>
							</div>
							<div class="col-lg-6 col-12">
								<p>Employee ID: {{ $user->userDetail->employee_id }}</p>
							</div>
						</div>
						<div class="row mb-4">
							<div class="col-lg-6 col-12">
								<p>Employee Designation: {{ $user->userDetail->designation->name }}</p>
							</div>
							<div class="col-lg-6 col-12">
								<p>Location: {{ $user->userDetail->locationMaster->working_location }}</p>
							</div>
						</div>
						<div class="row mb-4">
							<div class="col-lg-6 col-12">
								<p>Company Name: {{ $user->userDetail->companyMaster->company_name }}</p>
							</div>
							<div class="col-lg-6 col-12">
								<p>State: {{ $user->userDetail->state->name }}</p>
							</div>
						</div>
						<div class="row mb-4">
							<div class="col-lg-6 col-12">
								<p>Working Location: {{ $user->userDetail->locationMaster->working_location }}</p>
							</div>
							<div class="col-lg-6 col-12">
								<p>Division: {{ $user->userDetail->DivisonMaster->name }}</p>
							</div>
						</div>
						<hr>
						<table class="table table-bordered table-hover text-center table-light">
							<tbody>
								<tr class="table-active fw-bold">
									<td></td>
									<td>HQ Days</td>
									<td>Out Days</td>
									<td colspan="2">SUM (Rs.)</td>
								</tr>
								<tr>
									<td>DA (1-15)</td>
									<td>{{ $fifteen_days_of_monthly_expenses_headquarters_days }} Days</td>
									<td>{{ $fifteen_days_of_monthly_expenses_outstation_days }} Days</td>
									<td>Rs. {{ $fifteen_days_of_monthly_expenses_da }}</td>
									
								</tr>
								<tr>
									<td>DA (16-31)</td>
									<td>{{ $rest_of_monthly_expenses_headquarters_days }} Days</td>
									<td>{{ $rest_of_monthly_expenses_outstation_days }} Days</td>
									<td>Rs. {{ $rest_of_monthly_expenses_da }}</td>
									
								</tr>
								<tr>
									<td colspan="3">Total Of DA</td>
									<td colspan="2"><input type="text" class="form-control" disabled value="{{ $total_da }}"></td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered table-hover text-center table-light">
							<tbody>
								<tr class="table-active fw-bold">
									<td></td>
									<td>1-15</td>
									<td>16-31</td>
									<td colspan="2">SUM (Rs.)</td>
								</tr>
								<tr>
									<td>Travel</td>
									<td>Rs. {{ $fifteen_days_of_fare_amount }}</td>
									<td>Rs. {{ $rest_of_other_fare_amount }}</td>
									<td>Rs. {{ $total_other_fare_amount }}</td>
									
								</tr>
								<tr>
									<td>GENEX (Other Expenses)</td>
									<td>Rs. {{ $fifteen_days_of_other_expenses_amount }}</td>
									<td>Rs. {{ $rest_of_other_expenses_amount }}</td>
									<td>Rs. {{ $total_other_expenses_amount }}</td>
									
								</tr>
								<tr>
									<td>POSTAGE</td>
									<td>Rs. {{ $fifteen_days_of_monthly_expenses_postage }}</td>
									<td>Rs. {{ $rest_of_monthly_expenses_postage }}</td>
									<td>Rs. {{ $total_postage }}</td>
									
								</tr>
								<tr>
									<td>Mobile/ Internet</td>
									<td>Rs. {{ $fifteen_days_of_mobile_internet }}</td>
									<td>Rs. {{ $rest_of_monthly_mobile_internet }}</td>
									<td>Rs. {{ $total_mobile_internet }}</td>
									
								</tr>
								<tr>
									<td>Stationery</td>
									<td>Rs. {{ $fifteen_days_of_print_stationery }}</td>
									<td>Rs. {{ $rest_of_print_stationery }}</td>
									<td>Rs. {{ $total_print_stationery }}</td>									
								</tr>
								<tr>
									<td colspan="3">Total</td>
									<td colspan="2"><input type="text" class="form-control" disabled value="{{ $total }}"></td>
								</tr>
								<tr>
									<td colspan="3">Grand Total</td>
									<td colspan="2"><input type="text" value="{{ $grand_total }}" class="form-control" disabled></td>
								</tr>
							</tbody>
							<tfoot>
							    <tr>
							        
							        <td colspan="4"><a href="{{ route('expenses_slip_report') }}"><button type="button" class="btn btn-warning text-white float-end  me-4">Back</button></a>
							        </td>
							   </tr>
							</tfoot>
						</table>
                            
					</div>

					
				</div>
			</div>			
				
			</div>
		</div>
@endsection


@section('additional_script')
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
<script>
    $(document).ready(function() {
        $('#employee_id').on('input', function() {
            var employeeInput = $('#employee_id').val(); // Get the value from the single input field

            if (employeeInput.length >= 2) { // Trigger after 2 characters
                $.ajax({
                    url: '{{ route('employee.suggestions') }}',
                    method: 'GET',
                    data: { employee: employeeInput },
                    success: function(response) {
                        var suggestions = response.map(function(employee) {
                            return '<div class="suggestion-item" data-id="' + employee.employee_id + '">' + employee.formatted + '</div>';
                        }).join('');
                        
                        $('#employee-suggestions').html(suggestions).show();
                    }
                });
            } else {
                $('#employee-suggestions').empty().hide(); // Hide suggestions if input is too short
            }
        });

        // Handle click on a suggestion
        $(document).on('click', '.suggestion-item', function() {
            var employeeId = $(this).data('id'); // Get the employee_id from the selected suggestion

            // Set only the employee_id in the input field
            $('#employee_id').val(employeeId); // Fill the input field with the employee_id

            // Hide suggestions
            $('#employee-suggestions').empty().hide();
        });

        // Hide suggestions when clicking outside
        $(document).click(function(event) {
            if (!$(event.target).closest('#employee_id').length) {
                $('#employee-suggestions').empty().hide();
            }
        });
    });
</script>
<script>
    document.getElementById('generate-pdf').addEventListener('click', function () {
        // Send AJAX request to generate the PDF
        fetch('{{ route('expenses_slip_report_print') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({

                user: @json($user),
				monthName: @json($monthName),
				monthly_expenses: @json($monthly_expenses),
				fifteen_days_of_fare_amount: @json($fifteen_days_of_fare_amount),
				rest_of_other_fare_amount: @json($rest_of_other_fare_amount),
				total_other_fare_amount: @json($total_other_fare_amount),
				total: @json($total),
				grand_total: @json($grand_total),
				fifteen_days_of_other_expenses_amount: @json($fifteen_days_of_other_expenses_amount),
				rest_of_other_expenses_amount: @json($rest_of_other_expenses_amount),
				total_other_expenses_amount: @json($total_other_expenses_amount),
				fifteen_days_of_monthly_expenses_postage: @json($fifteen_days_of_monthly_expenses_postage),
				rest_of_monthly_expenses_postage: @json($rest_of_monthly_expenses_postage),
				total_postage: @json($total_postage),
				fifteen_days_of_mobile_internet: @json($fifteen_days_of_mobile_internet),
				rest_of_monthly_mobile_internet: @json($rest_of_monthly_mobile_internet),
				total_mobile_internet: @json($total_mobile_internet),
				fifteen_days_of_print_stationery: @json($fifteen_days_of_print_stationery),
				rest_of_print_stationery: @json($rest_of_print_stationery),
				total_print_stationery: @json($total_print_stationery),
				total_da: @json($total_da),
				fifteen_days_of_monthly_expenses_da: @json($fifteen_days_of_monthly_expenses_da),
				rest_of_monthly_expenses_da: @json($rest_of_monthly_expenses_da),
				fifteen_days_of_monthly_expenses_headquarters_days: @json($fifteen_days_of_monthly_expenses_headquarters_days),
				fifteen_days_of_monthly_expenses_outstation_days: @json($fifteen_days_of_monthly_expenses_outstation_days),
				rest_of_monthly_expenses_headquarters_days: @json($rest_of_monthly_expenses_headquarters_days),
				rest_of_monthly_expenses_outstation_days: @json($rest_of_monthly_expenses_outstation_days)

            })
        })
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'expense_slip_report.pdf';
            link.click();
        })
        .catch(error => {
            console.error('Error generating PDF:', error);
        });
    });
</script>
@endsection

@section('additional_style')

<style>
    .suggestions-list {
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        background-color: white;
        z-index: 9999;
    }

    .suggestion-item {
        padding: 8px;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background-color: #f0f0f0;
    }
</style>
@endsection