@extends('layouts.main')
@section('title', 'Location Master | Jmitra & Co. Pvt. Ltd')
@section('content')
@can('location-list')
		<!--******************* Main Content start *******************-->
		<div class="container-fluid px-4">
			@can('location-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addLocationModal">
						<i class="fa-solid fa-plus me-1"></i> Add Location
					</button>
				</div>
			</div>
			@endcan

			@can('location-search')
				@include('includes.search')
			@endcan
			@include('includes.message')

			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
						<h5 class="text-white">Location Master List</h5>
					</div>

					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
						          <th scope="col" style="width: 10%;">S.No.</th>
							      <th scope="col" style="width: 10%;">State</th>
							      <th scope="col" style="width: 10%;">City</th>
							      <th scope="col"style="width: 10%;">Working Location</th>	
							      <th scope="col"style="width: 10%;">Action</th>				              
					                
					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
								  	@foreach ($locations as $key => $location)
		                              <tr>
		                                <td>{{ $key + 1 }}</td>
									    <td>{{ $location->city ? $location->city->state->name : 'N/A' }}</td>  <!-- Correct relationship access -->
									    <td>{{ $location->city ? $location->city->name : 'N/A' }}</td> <!-- Same for city -->
									    <td>{{ $location->working_location }}</td>                                   
		                                <td>
		                                    <div class="d-flex">
		                                        @can('location-edit')
		                                        <span>
		                                            <a href="{{ route('location_master.edit', $location->id) }}">
		                                                <i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2"></i>
		                                            </a>
		                                        </span>                                      
		                                        @endcan
		                                        @can('location-delete')
		                                        <!-- <form method="POST" action="{{ route('location_master.destroy', $location->id) }}" style="display:inline" id="deleteForm-{{ $location->id }}">
		                                            @csrf
		                                            @method('DELETE')
		                                            <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
		                                               style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $location->id }}" 
		                                               title="Delete Location"></i>
		                                        </form>

		                                       
		                                        <div class="modal" tabindex="-1" id="deleteModal-{{ $location->id }}">
		                                            <div class="modal-dialog modal-dialog-centered">
		                                                <div class="modal-content">
		                                                    <div class="modal-header">
		                                                        <h5 class="modal-title">Delete</h5>
		                                                        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
		                                                    </div>
		                                                    <div class="modal-body">
		                                                        <h4>Are you sure you want to delete this Location?</h4>
		                                                    </div>
		                                                    <div class="modal-footer">
		                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		                                                        <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $location->id }}').submit();">Yes, Delete</button>
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

				
			</div>
			<!--*******************
					 Pagination Start
		 		*****************-->
				@include('includes.pagination')
				<!--*******************
					 Pagination End
		 		*****************-->


			@can('location-store')
			<!-- Add Location Modal -->
			<div class="modal" tabindex="-1" id="addLocationModal">
				@include('dash.LocationMaster.create')
			</div>
			@endcan

			
		</div>
		<!--******************* Main Content End *******************-->

<script>
// Load Location for Editing
// Load Location for Editing
function loadLocationDetails(locationId) {
    $.ajax({
        url: "{{ url('location_master') }}/" + locationId,
        type: "GET",
        success: function(data) {
            if (data.error) {
                alert("Error: " + data.error);
            } else {
                $('#state_id').val(data.state_id);
                $('#city_id').val(data.city_id);
                $('#working_location').val(data.working_location);
                populateCities(data.cities);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching Location details:", error);
            alert("Error fetching location details");
        }
    });
}

function loadLocations(locationId) {
    $.ajax({
        url: "{{ url('location_master') }}/" + locationId,
        method: 'GET',
        success: function(response) {
            // Pre-fill the form with location data
            // $('#state').val(response.state_id);
            $('#state').html('<option value="">Select State</option>');

            $('#city').html('<option value="">Select City</option>'); // Reset city dropdown

            // Populate city options based on the selected state
            response.cities.forEach(function(city) {
                $('#city').append(`<option value="${city.id}" ${city.id === response.city_id ? 'selected' : ''}>${city.name}</option>`);
            });

            // Populate city options based on the selected state
            response.states.forEach(function(state) {
                $('#state').append(`<option value="${state.id}" ${state.id === response.state_id ? 'selected' : ''}>${state.name}</option>`);
            });

            $('#working_locations').val(response.working_location);
            $('#editLocationForm').data('location-id', locationId); // Save the location ID for later use
            $('#EditLocationModal').modal('show');
        },
        error: function() {
            alert('Error fetching Location details.');
        }
    });
}


function updateLocation() {
		const locationId = $('#editLocationForm').data('location-id');
	    const companyData = {
	        state_id: $('#state_modal').val(),
	        city_id: $('#city_modal').val(),
	        working_location: $('#working_locations').val(),
	    };


	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('location_master') }}/" + locationId, {
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
	                $('#EditLocationModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error updating Location');
	        });
	}

$('#EditLocationModal').on('show.bs.modal', function () {
    // Example: Populate state and city dropdowns for the modal
    $('#state_modal').change(function () {
        let stateId = $(this).val();
        fetch(`/cities/${stateId}`)
            .then(response => response.json())
            .then(data => {
                let cityDropdown = $('#city_modal');
                cityDropdown.empty();
                cityDropdown.append('<option value="">Select City</option>');
                data.forEach(city => {
                    cityDropdown.append(`<option value="${city.id}">${city.name}</option>`);
                });
            })
            .catch(error => console.error('Error fetching cities:', error));
    });
});
			// Create Location
			function createLocation() {
				const locationData = {
					working_location: $('#working_locations').val(),
					city_id: $('#city_id').val(),
					city_id: $('#state_id').val(),
				};
				const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
				fetch("{{ route('location_master.store') }}", {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': token,
					},
					body: JSON.stringify(locationData),
				})
				.then(response => response.json())
				.then(data => {
					if (data.error) {
						alert(data.error);
					} else {
						alert(data.message);
						$('#addLocationModal').modal('hide');
						location.reload();
					}
				})
				.catch(error => {
					console.error('Error:', error);
					alert('Error creating Location');
				});
			}
document.addEventListener('change', function (e) {
    if (e.target && (e.target.id === 'state' || e.target.id === 'state_modal')) {
        let stateId = e.target.value;
        let citySelect = e.target.id === 'state' ? document.getElementById('city') : document.getElementById('city_modal');

        if (stateId) {
            fetch(`/cities/${stateId}`)
                .then(response => response.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                })
                .catch(error => console.error('Error fetching cities:', error));
        } else {
            citySelect.innerHTML = '<option value="">Select City</option>';
        }
    }
});


document.getElementById('state').addEventListener('change', function() {
    let stateId = this.value;
    if (stateId) {
        // Make AJAX call to fetch cities based on selected state
        fetch(`/cities/${stateId}`)
            .then(response => response.json())
            .then(data => {
                let citySelect = document.getElementById('city');
                citySelect.innerHTML = '<option value="">Select City</option>';  // Reset city dropdown
                // Loop through the cities and populate the city dropdown
                data.forEach(city => {
                    citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                });
            });
    } else {
        // If no state selected, reset city dropdown
        document.getElementById('city').innerHTML = '<option value="">Select City</option>';
    }
});


		document.getElementById('submitLocation').addEventListener('click', function() {
		    const locationData = {
		        state_id: document.getElementById('state').value,
		        city_id: document.getElementById('city').value,
		        working_location: document.getElementById('working_location').value,
		    };

		    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

		    // Make sure all required fields are filled
		    if (!locationData.state_id || !locationData.city_id || !locationData.working_location) {
		        alert('Please fill in all fields.');
		        return;
		    }

		    fetch("{{ route('location_master.store') }}", {
		        method: 'POST',
		        headers: {
		            'Content-Type': 'application/json',
		            'X-CSRF-TOKEN': token,
		        },
		        body: JSON.stringify(locationData),
		    })
		    .then(response => response.json())
		    .then(data => {
		        if (data.error) {
		            alert(data.error);
		        } else {
		            alert(data.message);
		            $('#addLocationModal').modal('hide');
		            location.reload();  // Reload the page to show the new location in the list
		        }
		    })
		    .catch(error => {
		        console.error('Error:', error);
		        alert('Error creating location');
		    });
		});
</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
