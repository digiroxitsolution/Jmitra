@extends('layouts.main')
@section('title', 'Designation Master | Jmitra & Co. Pvt. Ltd')
@section('content')
@can('designation-list')

	<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('designation-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addDesignationModal"><i class="fa-solid fa-plus me-1"></i> Add Designation</button>
				</div>
			</div>
			@endcan

			@can('designation-search')
				@include('includes.search')
			@endcan

			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Designation Master List</h5>
					</div>
					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
						            <th scope="col" style="width: 10%;">S.No.</th>
							      	<th scope="col" style="width: 10%;">Name</th>
							      	
							      	
							      	<th scope="col" style="width: 10%;">Action</th>				              
					                
					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
								  	@foreach ($designations as $key => $designation)
		                              <tr>
		                                <td>{{ ++$i }}</td>                                
		                                <td>{{ $designation->name }}</td>
		                                                                  
		                                
		                                <td>
		                                    <div class="d-flex">
		                                    @can('designation-edit')
		                                    <span>
		                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#EditDesignationModal" 
		                                               onclick="loadDesignation('{{ $designation->id }}', 'edit')">
								      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
								      			</a>
								      		</span>                                        
		                                    @endcan                                                


		                                    @can('designation-delete')
		                                    <!-- <form method="POST" action="{{ route('designation.destroy', $designation->id) }}" style="display:inline" id="deleteForm-{{ $designation->id }}">
		                                        @csrf
		                                        @method('DELETE')
		                                        
		                                     
		                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
		                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $designation->id }}" 
		                                           title="Delete mode of expense"></i>
		                                    </form>

		                                  
		                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $designation->id }}">
		                                        <div class="modal-dialog modal-dialog-centered">
		                                            <div class="modal-content">
		                                                <div class="modal-header">
		                                                    <h5 class="modal-title">Delete</h5>
		                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
		                                                </div>
		                                                <div class="modal-body">
		                                                    <h4>Are you sure you want to delete this Designation ?</h4>
		                                                </div>
		                                                <div class="modal-footer">
		                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $designation->id }}').submit();">Yes, Delete</button>
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



		 		@can('designation-store')
				<!--*******************
					 Add designation Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addDesignationModal">
					@include('dash.designation.create')
				  </div>
				<!--*******************
					 Add designation Master End
		 		*****************-->
		 		@endcan

		 		@can('designation-update')
		 		<!--*******************
					 Edit designation Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="EditDesignationModal">
					@include('dash.designation.edit')
				  </div>
				<!--*******************
					 Edit designation Master End
		 		*****************-->
		 		@endcan
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->	
		

<script>
	let selectedDesignationId = null;

	function loadDesignation(DesignationId) {
	    selectedDesignationId = DesignationId; // Set the selected designation ID globally
	    $.ajax({
	        url: "{{ url('designation') }}/" + DesignationId,
	        method: 'GET',
	        success: function (response) {
	            $('#name').val(response.name);
	            
	            
	            $('#description').val(response.description);
	            $('#EditDesignationModal').modal('show'); // Open the modal
	        },
	        error: function () {
	            alert('Error fetching designation data.');
	        },
	    });
	}







function UpdateDesignation(DesignationId) {
	    const companyData = {
	        name: $('#name').val(),
	        description: $('#description').val(),
	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('designation') }}/" + DesignationId, {
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
	                $('#EditDesignationModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error updating designation');
	        });
	}




function CreateDesignation() {
	    const companyData = {
	        name: $('#names').val(),
	        description: $('#descriptions').val(),
	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ route('designation.store') }}", {
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
	                $('#addDesignationModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error creating designation');
	        });
	}

</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
