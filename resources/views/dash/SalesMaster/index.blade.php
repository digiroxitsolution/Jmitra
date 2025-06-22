@extends('layouts.main')
@section('title', 'Sales Master | Jmitra & Co. Pvt. Ltd')
@section('content')	
@can('sales-list')

	<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<!-- <button class="btn add me-3 shadow" type="button">Merge</button> -->
					<button class="btn btn-success me-3 shadow" type="button" data-bs-toggle="modal" data-bs-target="#uploadFileModal">Upload</button>
					
				</div>
			</div>
			@include('includes.search')
			@include('includes.message')

			
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Sales Master List</h5>
					</div>

					


					<div class="table-responsive">


					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
					                <th scope="col" style="width: 5%;"><input class="form-check-input" type="checkbox"></th>
					                <th style="width: 10%;">S.No.</th>
					                <th style="width: 40%;">File Name</th>
					                <th style="width: 20%;">Date of Upload</th>
					                <th style="width: 25%;">Action</th>
					            </tr>
					        </thead>
					        <tbody>
					            @php $i = 0; @endphp
					            @foreach ($sales as $key => $sale)
					            <tr>
					                <td scope="row"><input class="form-check-input" type="checkbox"></td>
					                <td>{{ ++$i }}</td>
					                <td class="text-truncate" style="max-width: 200px;">{{ $sale->file_name }}</td>
					                <td>{{ \Carbon\Carbon::parse($sale->date_of_upload)->format('d-m-Y') }}</td>
					                <td>
					                    <div class="d-flex">
					                    	@can('sales-show')
					                        <span>
					                            <a href="{{ route('sales_master.show', $sale->id) }}">
					                                <i class="fa-solid fa-eye bg-info text-white p-1 rounded-circle shadow me-2"></i>
					                            </a>
					                        </span>
					                        @endcan
					                        @can('sales-edit')
					                        <span>
					                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editSalesMasterModal" 
					                               onclick="loadSalesMAster('{{ $sale->id }}', 'edit')">
					                                <i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2"></i>
					                            </a>
					                        </span>
					                        @endcan
					                        @can('sales-delete')
					                        <form method="POST" action="{{ route('sales_master.destroy', $sale->id) }}" style="display:inline" id="deleteForm-{{ $sale->id }}">
					                            @csrf
					                            @method('DELETE')
					                            <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
					                               style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $sale->id }}" 
					                               title="Delete mode of expense"></i>
					                        </form>
					                        <div class="modal" tabindex="-1" id="deleteModal-{{ $sale->id }}">
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
					                                        <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $sale->id }}').submit();">Yes, Delete</button>
					                                    </div>
					                                </div>
					                            </div>
					                        </div>
					                        @endcan
					                        @can('sales-show')
					                        <!-- <span>
					                            <i class="fa-solid fa-eye bg-success text-white p-1 rounded-circle shadow"></i>
					                        </span> -->
					                        @endcan
					                    </div>
					                </td>
					            </tr>
					            @endforeach
					             
					        </tbody>
					    </table>
					</div>

				</div>

				@include('includes.pagination')
		 		
				@include('dash.SalesMaster.upload')

		 		

		 		@can('sales-update')
				<!--*******************
					 Edit Sales Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="editSalesMasterModal">
					@include('dash.SalesMaster.edit')
				  </div>
				<!--*******************
					 Edit Sales Master End
		 		*****************-->
		 		@endcan


			</div>
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
	                $('#file_names').val(response.file_name); // File name
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
    const file_name = $('#file_names').val();
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




</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
