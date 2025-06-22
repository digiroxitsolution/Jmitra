@extends('layouts.main')
@section('title', 'Expenses Slip Report| Jmitra & Co. Pvt. Ltd')
@section('content')	
<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<div class="d-flex justify-content-between">
								<h5 class="text-white">Expense Slip Report</h5>

								

							</div>
					</div>
					@include('includes.message')
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

				</div>
			</div>			
				
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->

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