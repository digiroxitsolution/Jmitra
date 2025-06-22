@extends('layouts.main')
@section('title', 'Divison Master | Jmitra & Co. Pvt. Ltd')
@section('content')	
@can('divison-list')	
		<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('divison-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addDivisionMasterModal"><i class="fa-solid fa-plus me-1"></i> Add Division</button>
				</div>
			</div>
			@endcan

			@can('divison-search')
				@include('includes.search')
			@endcan

			@can('divison-list')
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Division Master List</h5>
					</div>

					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					        	<tr>
							      <th scope="col" style="width: 10%;">S.No.</th>
							      <th scope="col" style="width: 10%;">Division Name</th>
							      <th scope="col" style="width: 10%;">Action</th>
							    </tr>


					            
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
							  	@foreach ($divions as $key => $divion)
	                              <tr>
	                                <td>{{ ++$i }}</td>                                
	                                <td>{{ $divion->name }}</td>                                  
	                                <td>
	                                    <div class="d-flex">
	                                    @can('divison-edit')
	                                    <span>
	                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#EditDivisionMasterModal" 
	                                               onclick="loadDivisonDetail('{{ $divion->id }}', 'edit')">
							      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
							      			</a>
							      		</span>                                        
	                                    @endcan                                                


	                                    @can('divison-delete')
	                                    <!-- <form method="POST" action="{{ route('divison_master.destroy', $divion->id) }}" style="display:inline" id="deleteForm-{{ $divion->id }}">
	                                        @csrf
	                                        @method('DELETE')
	                                        
	                                        
	                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
	                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $divion->id }}" 
	                                           title="Delete divion"></i>
	                                    </form>

	                                    
	                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $divion->id }}">
	                                        <div class="modal-dialog modal-dialog-centered">
	                                            <div class="modal-content">
	                                                <div class="modal-header">
	                                                    <h5 class="modal-title">Delete</h5>
	                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
	                                                </div>
	                                                <div class="modal-body">
	                                                    <h4>Are you sure you want to delete this divion?</h4>
	                                                </div>
	                                                <div class="modal-footer">
	                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $divion->id }}').submit();">Yes, Delete</button>
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
		 		

		 		@can('divison-store')
				<!--*******************
					 Add Divison Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addDivisionMasterModal">
					@include('dash.DivisonMaster.create')
				  </div>
				<!--*******************
					 Add Division Master End
		 		*****************-->
		 		@endcan

		 		@can('divison-update')
		 		<!--*******************
					 Edit Divison Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="EditDivisionMasterModal">
				 	@include('dash.DivisonMaster.edit')					
				  </div>
				<!--*******************
					 Edit Division Master End
		 		*****************-->
		 		@endcan


			</div>
			@endcan
		</div>
		<!--*******************
			Main Content End
		 *****************-->

<script>
	let selectedDivisonId = null;
    function loadDivisonDetail(divisonId) {
    	 selectedDivisonId = divisonId; // Save the selected Divison ID
    	$.ajax({
            url: "{{ url('divison_master') }}/" + divisonId,  // Make sure this URL is correct
            method: 'GET',
            success: function(response) {
                // Populate modal with user data
                $('#name').val(response.name);
                // Show the modal
                $('#EditDivisionMasterModal').modal('show');
            },
            error: function() {
                alert('Error fetching Divison details.');
            }
        });        
    }



    function UpdateDivison(divisonId) {
	    const divisonData = {
	        name: $('#name').val(),
	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('divison_master') }}/" + divisonId, {
	        method: 'PUT',
	        headers: {
	            'Content-Type': 'application/json',
	            'X-CSRF-TOKEN': token,
	        },
	        body: JSON.stringify(divisonData),
	    })
	        .then(response => response.json())
	        .then(data => {
	            if (data.error) {
	                alert(data.error);
	            } else {
	                alert(data.message);
	                $('#EditDivisionMasterModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error updating divison');
	        });
	}

	function CreateDivison() {
	    const divisonData = {
	        name: $('#divison_name').val(),	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ route('divison_master.store') }}", {
	        method: 'POST',
	        headers: {
	            'Content-Type': 'application/json',
	            'X-CSRF-TOKEN': token,
	        },
	        body: JSON.stringify(divisonData),
	    })
	        .then(response => response.json())
	        .then(data => {
	            if (data.error) {
	                alert(data.error);
	            } else {
	                alert(data.message);
	                $('#addDivisionMasterModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error creating Divison');
	        });
	}
</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
