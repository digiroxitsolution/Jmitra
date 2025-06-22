@extends('layouts.main')
@section('title', 'City | Jmitra & Co. Pvt. Ltd')
@section('content')
@can('city-list')
<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('city-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">		
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addCityModal"><i class="fa-solid fa-plus me-1"></i> <i class="bi bi-plus-circle"></i> Add City</button>
				</div>
			</div>
			@endcan

			@can('city-search')
				@include('includes.search')
			@endcan

			@include('includes.message')
			
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">City List</h5>
					</div>
					<div class="table-responsive">
						<table id="example" class="table bg-white rounded shadow-sm table-hover">
						  <thead>
						    <tr>
							  
						      <th scope="col">S.No. <i class="fa-solid fa-sort ms-2"></i></th>
						      <th scope="col">State Name <i class="fa-solid fa-sort ms-2"></i></th>
						      <th scope="col">City Name <i class="fa-solid fa-sort ms-2"></i></th>
						      <th scope="col">Action <i class="fa-solid fa-sort ms-2"></i></th>
						    </tr>
						  </thead>
						  <tbody>
						  	@php $i = 0; @endphp
						  	@foreach ($cities as $key => $city)
                              <tr>
                                <td>{{ ++$i }}</td>                                
                                <td>{{ $city->State->name }}</td>
                                
                                <td>{{ $city->name }}</td>                                  
                                <td>
                                    <div class="d-flex">
                                    @can('city-edit')
                                    <span>
                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editCityModal" 
                                               onclick="loadCity('{{ $city->id }}', 'edit')">
						      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
						      			</a>
						      		</span>                                        
                                    @endcan                                                


                                    @can('city-delete')
                                    <!-- <form method="POST" action="{{ route('city.destroy', $city->id) }}" style="display:inline" id="deleteForm-{{ $city->id }}">
                                        @csrf
                                        @method('DELETE')
                                        
                                        
                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $city->id }}" 
                                           title="Delete Expense
                                           Type"></i>
                                    </form>

                                    
                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $city->id }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete</h5>
                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4>Are you sure you want to delete this Expense?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $city->id }}').submit();">Yes, Delete</button>
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

				@include('includes.pagination')
				
		 		@can('city-store')				
				<!--*******************
					 Add City Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addCityModal">
					@include('City.create')
				  </div>
				<!--*******************
					 Add City End
		 		*****************-->
		 		@endcan


		 		@can('city-update')
		 		<!--*******************
					 Edit City Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="editCityModal">
					@include('City.edit')
				  </div>
				<!--*******************
					 Edit City End
		 		*****************-->
		 		@endcan
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->

<script>
	let selectedCityId = null;
	function loadCity(cityId) {
	    selectedCityId = cityId; // Corrected the variable name
	    $.ajax({
	        url: "{{ url('city') }}/" + cityId,
	        type: 'GET',
	        success: function (response) {
	            // Populate the edit modal fields
	            $('#editCityModal #state_id').val(response.state_id);
	            $('#editCityModal #name').val(response.name);
	            $('#editCityModal').data('city-id', response.id); // Store cityId for use during update
	            $('#editCityModal').modal('show');
	        },
	        error: function () {
	            alert('Error fetching City details.');
	        }
	    });
	}

	function updateCity() {
	    const cityId = $('#editCityModal').data('city-id'); // Retrieve the stored cityId
	    const cityData = {
	        name: $('#editCityModal #name').val(),
	        state_id: $('#editCityModal #state_id').val(),
	    };
	    const token = $('meta[name="csrf-token"]').attr('content'); // Fetch CSRF token

	    $.ajax({
	        url: "{{ url('city') }}/" + cityId,
	        type: 'PUT',
	        headers: {
	            'X-CSRF-TOKEN': token,
	        },
	        data: cityData,
	        success: function (response) {
	            if (response.message) {
	                alert(response.message); // Show success message
	            }
	            $('#editCityModal').modal('hide'); // Close the modal
	            location.reload(); // Reload the page to reflect changes
	        },
	        error: function (xhr) {
	            const errorMessage = xhr.responseJSON?.message || 'Error updating the city.';
	            alert('Error: ' + errorMessage);
	        }
	    });
	}



	function CreateCity() {
	    const cityData = {
	        name: $('#addCityModal #name').val(),
	        state_id: $('#addCityModal #state_id').val(),
	    };
	    const token = $('meta[name="csrf-token"]').attr('content');

	    $.ajax({
	        url: "{{ route('city.store') }}",
	        type: 'POST',
	        headers: {
	            'X-CSRF-TOKEN': token,
	        },
	        data: cityData,
	        success: function(response) {
	            alert(response.message);
	            $('#addCityModal').modal('hide');
	            location.reload();
	        },
	        error: function(xhr) {
	            alert('Error: ' + xhr.responseJSON.message);
	        }
	    });
	}

</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
