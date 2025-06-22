@extends('layouts.main')
@section('title', 'Rejection Master | Jmitra & Co. Pvt. Ltd')
@section('content')	
@can('rejection-list')

	<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('rejection-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addRejectedReasonModal"><i class="fa-solid fa-plus me-1"></i> Add Rejected Reason</button>
				</div>
			</div>
			@endcan

			@can('rejection-search')
				@include('includes.search')
			@endcan

			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Rejected Master List</h5>
					</div>
					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
						            <th scope="col" style="width: 10%;">S.No.</th>
							      	<th scope="col" style="width: 10%;">Reason of Rejection</th>
							      	<th scope="col" style="width: 10%;">Action</th>				              
					                
					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
								  	@foreach ($rejections as $key => $rejection)
		                              <tr>
		                                <td>{{ ++$i }}</td>                                
		                                <td>{{ $rejection->reason_of_rejection }}</td>                                  
		                                <td>
		                                    <div class="d-flex">
		                                    @can('rejection-edit')
		                                    <span>
		                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#EditRejectedReasonModal" 
		                                               onclick="loadRejection('{{ $rejection->id }}', 'edit')">
								      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
								      			</a>
								      		</span>                                        
		                                    @endcan                                                


		                                    @can('rejection-delete')
		                                    <!-- <form method="POST" action="{{ route('rejection_master.destroy', $rejection->id) }}" style="display:inline" id="deleteForm-{{ $rejection->id }}">
		                                        @csrf
		                                        @method('DELETE')
		                                        
		                                       
		                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
		                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $rejection->id }}" 
		                                           title="Delete mode of expense"></i>
		                                    </form>

		                                   
		                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $rejection->id }}">
		                                        <div class="modal-dialog modal-dialog-centered">
		                                            <div class="modal-content">
		                                                <div class="modal-header">
		                                                    <h5 class="modal-title">Delete</h5>
		                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
		                                                </div>
		                                                <div class="modal-body">
		                                                    <h4>Are you sure you want to delete this Rejected Reason ?</h4>
		                                                </div>
		                                                <div class="modal-footer">
		                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $rejection->id }}').submit();">Yes, Delete</button>
		                                                </div>
		                                            </div>
		                                        </div>
		                                    </div> -->
		                                @endcan                             
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



		 		@can('rejection-store')
				<!--*******************
					 Add Rejection Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addRejectedReasonModal">
					@include('dash.RejectionMaster.create')
				  </div>
				<!--*******************
					 Add Rejection Master End
		 		*****************-->
		 		@endcan

		 		@can('rejection-update')
		 		<!--*******************
					 Edit Rejection Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="EditRejectedReasonModal">
					@include('dash.RejectionMaster.edit')
				  </div>
				<!--*******************
					 Edit Rejection Master End
		 		*****************-->
		 		@endcan
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->	
		

<script>
	let selectedRejectionId = null;
    function loadRejection(RejectionId) {
    	 selectedRejectionId = RejectionId; // Save the selected Divison ID
    	$.ajax({
            url: "{{ url('rejection_master') }}/" + RejectionId,  // Make sure this URL is correct
            method: 'GET',
            success: function(response) {
                // Populate modal with user data
                $('#reason_of_rejections').val(response.reason_of_rejection);
                // Show the modal
                $('#EditRejectedReasonModal').modal('show');
            },
            error: function() {
                alert('Error fetching Reason of Rejection.');
            }
        });        
    }



function UpdateRejection(RejectionId) {
	    const companyData = {
	        reason_of_rejection: $('#reason_of_rejections').val(),
	        location: $('#locations').val(),
	        address: $('#addresss').val(),
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('rejection_master') }}/" + RejectionId, {
	        method: 'PUT',
	        headers: {
	            'Content-Type': 'application/json',
	            'X-CSRF-TOKEN': token,
	        },
	        body: JSON.stringify(companyData),
	    })
	        .then(response => response.json())
	        .then(data => {
	            if (data.error) {
	                alert(data.error);
	            } else {
	                alert(data.message);
	                $('#EditRejectedReasonModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error updating company');
	        });
	}
function CreateRejection() {
	    const companyData = {
	        reason_of_rejection: $('#reason_of_rejection').val(),
	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ route('rejection_master.store') }}", {
	        method: 'POST',
	        headers: {
	            'Content-Type': 'application/json',
	            'X-CSRF-TOKEN': token,
	        },
	        body: JSON.stringify(companyData),
	    })
	        .then(response => response.json())
	        .then(data => {
	            if (data.error) {
	                alert(data.error);
	            } else {
	                alert(data.message);
	                $('#addRejectedReasonModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error creating Rejection');
	        });
	}




</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
