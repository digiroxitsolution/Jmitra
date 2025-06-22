@extends('layouts.main')
@section('title', 'General Expenses | Jmitra & Co. Pvt. Ltd')
@section('content')	
<!--*******************
            Main Content start
        *******************-->
        <div class="container-fluid px-4">
            @include('includes.message')
            <div class="row my-5">
                <div class="col">
                        <div class="p-4 position-relative rounded-top-4">
                            <form action="{{ route('general_expenses') }}" method="POST">
                                    @csrf
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="monthName" class="form-label">Select Month</label>                                    
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
                        
            </div>
            
            @if(isset($ExpenseDetailsReports))
            <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                        <h5 class="text-white">General Expense</h5>
                            <form action="{{ route('general_expenses_print') }}" method="POST">
                                @csrf
                                
                                        
                                <input type="month" name="monthName" id="monthName" class="form-control" value="{{ $print_data['monthName'] }}" hidden>
                                <input type="date" name="fromDate" id="fromDate" class="form-control" value="{{ $print_data['fromDate'] }}" hidden> 
                                <input type="date" name="toDate" id="toDate" class="form-control" value="{{ $print_data['toDate'] }}" hidden>                                                       
                                <button type="submit" class="btn btn-warning text-white">Print</button>
                                
                                
                            </form>
                    </div>
                    <div class="bg-white p-4">
                        <div class="text-center p-5">
                            <h5 class="text-decoration-underline mb-3">@if(isset($monthName) && $monthName)
                                ( {{ \Carbon\Carbon::parse($monthName)->format('F Y') }} )
                            @endif
                            @if(isset($fromDate) && isset($toDate) && $fromDate && $toDate)
                                ( {{ \Carbon\Carbon::parse($fromDate)->format('j F, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('j F, Y') }})
                            @endif</h5>
                            <h6 class="text-decoration-underline mb-3">General Expense</h6>
                            <button type="button" class="btn btn-info text-white mb-3">{{ $all_total_genex }}</button>
                            <div class="d-flex justify-content-center mb-3">
                                <button type="button" class="btn btn-info text-white me-3"></button>
                                <h5 class="text-decoration-underline">General Expenses</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!--*******************
            Main Content End
         *****************-->
		

<script>
	let selectedSalesMAsterId = null;

	function loadSalesMAster(SalesMAsterId) {
	    selectedSalesMAsterId = SalesMAsterId; // Save the selected ID
	    $.ajax({
	        url: "{{ url('sales_master') }}/" + SalesMAsterId,  // Make sure this URL is correct
	        method: 'GET',
	        success: function(response) {
	            console.log('Response:', response); // Log the response for debugging
	            if (response.error) {
	                alert(response.error); // If error is returned, show an alert
	            } else {
	                // If no error, populate modal fields
	                $('#file_name').val(response.file_name); // File name
	                $('#date_of_upload').val(response.date_of_upload); // Date of upload
	                // Show the modal
	                $('#editSalesMasterModal').modal('show');
	            }
	        },
	        error: function(xhr, status, error) {
	            console.error('AJAX Error:', status, error); // Log AJAX errors
	            alert('Error fetching Sales Master.');
	        }
	    });
	}






function UpdateSalesMAster(SalesMAsterId) {
    const file_name = $('#file_name').val();
    const date_of_upload = $('#date_of_upload').val();

    // Validate inputs
    if (!file_name.trim()) {
        alert('File Name is required.');
        return;
    }

    // if (!date_of_upload.trim()) {
    //     alert('Date of Upload is required.');
    //     return;
    // }

    const data = { 
        file_name: file_name, 
        date_of_upload: date_of_upload
    };

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch("{{ url('sales_master') }}/" + SalesMAsterId, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(data),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data); // Log response for debugging
        if (data.error) {
            alert(data.error);
        } else {
            alert(data.message);
            $('#editSalesMasterModal').modal('hide');
            location.reload();  // Reload the page to reflect changes
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating Sales Master');
    });
}



function CreateSalesMAster() {
    const state = $('#state').val();
    const amount = $('#amount').val();
    const date = $('#date').val();

    if (!state.trim()) {
        alert('File Name is required.');
        return;
    }
    if (!amount.trim()) {
        alert('Date Of Upload is required.');
        return;
    }

    const data = { state, amount }; // Short-hand syntax
     // const data = { amount }; // Short-hand syntax
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch("{{ route('sales_master.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            if (!response.ok) {
                return response.text().then(html => {
                    console.error('Error HTML:', html); // Log HTML for debugging
                    throw new Error('Failed to create Sales Master.');
                });
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            $('#addSalesMasterModal').modal('hide');
            location.reload(); // Reload the page to fetch updated data
        })
        .catch(error => {
            console.error('Error:', error.message);
            alert('Error creating Sales Master: ' + error.message);
        });
}





</script>
@endsection
