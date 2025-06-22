@extends('layouts.main')
@section('title', 'Way Of Location Master | Jmitra & Co. Pvt. Ltd')
@section('content')	
@can('way-of-location-list')	
		<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('way-of-location-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addWayOfLocationModal"><i class="fa-solid fa-plus me-1"></i> Add Way Location</button>
				</div>
			</div>
			@endcan 
			@can('way-of-location-search')
				@include('includes.search')
			@endcan 
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Way of Location Master List</h5>
					</div>

					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
						            <th scope="col" style="width: 10%;">S.No.</th>
							      	<th scope="col" style="width: 10%;">Way of Loaction</th>
							      	<th scope="col" style="width: 10%;">Action</th>

					             
					                
					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
								  	@foreach ($way_of_locations as $key => $way_of_location)
		                              <tr>
		                                <td>{{ ++$i }}</td>                                
		                                <td>{{ $way_of_location->way_of_location }}</td>                                  
		                                <td>
		                                    <div class="d-flex">
		                                    @can('way-of-location-edit')
		                                    <span>
		                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#EditDivisionMasterModal" 
		                                               onclick="loadWayOfLocationDetail('{{ $way_of_location->id }}', 'edit')">
								      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
								      			</a>
								      		</span>                                        
		                                    @endcan                                                


		                                    @can('way-of-location-delete')
		                                    <!-- <form method="POST" action="{{ route('way_of_location_master.destroy', $way_of_location->id) }}" style="display:inline" id="deleteForm-{{ $way_of_location->id }}">
		                                        @csrf
		                                        @method('DELETE')
		                                        
		                                       
		                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
		                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $way_of_location->id }}" 
		                                           title="Delete mode of expense"></i>
		                                    </form>

		                                   
		                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $way_of_location->id }}">
		                                        <div class="modal-dialog modal-dialog-centered">
		                                            <div class="modal-content">
		                                                <div class="modal-header">
		                                                    <h5 class="modal-title">Delete</h5>
		                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
		                                                </div>
		                                                <div class="modal-body">
		                                                    <h4>Are you sure you want to delete this mode of way of location?</h4>
		                                                </div>
		                                                <div class="modal-footer">
		                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $way_of_location->id }}').submit();">Yes, Delete</button>
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


		 		
		 		@can('way-of-location-store')
				<!--*******************
					 Add Way of Location  Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addWayOfLocationModal">
					@include('dash.WayOfLocationMaster.create')
				 </div>
				<!--*******************
					 Add Way of Location End
		 		*****************-->
		 		@endcan
		 		@can('way-of-location-update')
		 		<!--*******************
					 Edit Way of Location  Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="editWayOfLocationModal">
					@include('dash.WayOfLocationMaster.edit')
				 </div>
				<!--*******************
					 Edit Way of Location End
		 		*****************-->
		 		@endcan

			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->

<script>
	let selectedWayOfLocationId = null;
    function loadWayOfLocationDetail(WayOfLocationId) {
    	 selectedWayOfLocationId = WayOfLocationId; // Save the selected Divison ID
    	$.ajax({
            url: "{{ url('way_of_location_master') }}/" + WayOfLocationId,  // Make sure this URL is correct
            method: 'GET',
            success: function(response) {
                // Populate modal with user data
                $('#way_of_locations').val(response.way_of_location);
                // Show the modal
                $('#editWayOfLocationModal').modal('show');
            },
            error: function() {
                alert('Error fetching Way Of Location details.');
            }
        });        
    }



function UpdateWayOfLocation(WayOfLocationId) {
    const way_of_location = $('#way_of_locations').val();
    if (!way_of_location.trim()) {
        alert('Way of Location is required.');
        return;
    }

    const data = { way_of_location }; // Use short-hand syntax
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch("{{ url('way_of_location_master') }}/" + WayOfLocationId, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Log response for debugging
            if (data.error) {
                alert(data.error);
            } else {
                alert(data.message);
                $('#editWayOfLocationModal').modal('hide');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating Way Of Location');
        });
}



function CreateWayOfLocation() {
	    const companyData = {
	        way_of_location: $('#way_of_location').val(),
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ route('way_of_location_master.store') }}", {
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
	                $('#addWayOfLocationModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error creating Way Of Location');
	        });
	}

</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
