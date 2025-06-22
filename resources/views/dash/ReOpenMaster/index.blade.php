@extends('layouts.main')
@section('title', 'Re-Open Master | Jmitra & Co. Pvt. Ltd')
@section('content')
@can('re-open-list')
	<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('re-open-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addReOpenModal"><i class="fa-solid fa-plus me-1"></i> Add Re-Open</button>
				</div>
			</div>
			@endcan
			@can('re-open-search')
				@include('includes.search')
			@endcan
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Re-Open Master List</h5>
					</div>
					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
						           <th scope="col" style="width: 10%;">S.No.</th>
							       <th scope="col" style="width: 10%;">Reason of Re - Open</th>
							       <th scope="col" style="width: 10%;">Action</th>	      	

					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
							  	@foreach ($reOpenForms as $key => $reOpenForm)
	                              <tr>
	                                <td>{{ ++$i }}</td>                                
	                                <td>{{ $reOpenForm->reason_of_re_open }}</td>                                  
	                                <td>
	                                    <div class="d-flex">
	                                    @can('re-open-edit')
	                                    <span>
	                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#EditReOpenModal" 
	                                               onclick="loadReOpen('{{ $reOpenForm->id }}', 'edit')">
							      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
							      			</a>
							      		</span>                                        
	                                    @endcan                                                


	                                    @can('re-open-delete')
	                                    <!-- <form method="POST" action="{{ route('re_open_master.destroy', $reOpenForm->id) }}" style="display:inline" id="deleteForm-{{ $reOpenForm->id }}">
	                                        @csrf
	                                        @method('DELETE')
	                                        
	                                       
	                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
	                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $reOpenForm->id }}" 
	                                           title="Delete mode of expense"></i>
	                                    </form>

	                                  
	                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $reOpenForm->id }}">
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
	                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $reOpenForm->id }}').submit();">Yes, Delete</button>
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



		 		@can('re-open-store')
				<!--*******************
					 Add Re - Open Reason Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addReOpenModal">
					@include('dash.ReOpenMaster.create')
				  </div>
				<!--*******************
					 Add Re - Open Reason Master End
		 		*****************-->
		 		@endcan
		 		@can('re-open-store')
		 		<!--*******************
					 Edit Re - Open Reason Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="EditReOpenModal">
					@include('dash.ReOpenMaster.edit')
				  </div>
				<!--*******************
					Edit Re - Open Reason Master End
		 		*****************-->
		 		@endcan
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->

	
		

<script>
	let selectedRejectionId = null;
    function loadReOpen(RejectionId) {
    	 selectedRejectionId = RejectionId; // Save the selected Divison ID
    	$.ajax({
            url: "{{ url('re_open_master') }}/" + RejectionId,  // Make sure this URL is correct
            method: 'GET',
            success: function(response) {
                // Populate modal with user data
                $('#reason_of_re_opens').val(response.reason_of_re_open);
                // Show the modal
                $('#EditReOpenModal').modal('show');
            },
            error: function() {
                alert('Error fetching Reason of Re-Open.');
            }
        });        
    }

function UpdateReOpen(RejectionId) {
	    const companyData = {
	        reason_of_re_open: $('#reason_of_re_opens').val(),
	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('re_open_master') }}/" + RejectionId, {
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
	                $('#EditReOpenModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error updating company');
	        });
	}




function CreateReOpen() {
	    const companyData = {
	        reason_of_re_open: $('#reason_of_re_open').val(),
	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ route('re_open_master.store') }}", {
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
	                $('#addReOpenModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error creating Re-Open');
	        });
	}
</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
