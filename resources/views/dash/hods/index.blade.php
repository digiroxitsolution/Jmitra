
@extends('layouts.main')
@section('title', 'Hods Master | Jmitra & Co. Pvt. Ltd')
@section('content')	
@can('hod-list')
	<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('hod-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addHodModal"><i class="fa-solid fa-plus me-1"></i> Add Hod</button>
				</div>
			</div>
			@endcan

			@can('hod-search')
				@include('includes.search')
			@endcan

			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Hods Master List</h5>
					</div>
					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
						            <th scope="col" style="width: 10%;">S.No.</th>
							      	<th scope="col" style="width: 10%;">Hod Name</th>
							      	
							      	<th scope="col" style="width: 10%;">Action</th>				              
					                
					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
								  	@foreach ($hods as $key => $hod)
		                              <tr>
		                                <td>{{ ++$i }}</td>                                
		                                <td>{{ $hod->name }}</td>
		                                
		                                <td>
		                                    <div class="d-flex">
		                                    @can('hod-edit')
		                                    <span>
		                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#EditHodModal" 
		                                               onclick="loadHod('{{ $hod->id }}', 'edit')">
								      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
								      			</a>
								      		</span>                                        
		                                    @endcan                                                


		                                    @can('hod-delete')
		                                    <!-- <form method="POST" action="{{ route('hods.destroy', $hod->id) }}" style="display:inline" id="deleteForm-{{ $hod->id }}">
		                                        @csrf
		                                        @method('DELETE')
		                                        
		                                       
		                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
		                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $hod->id }}" 
		                                           title="Delete mode of expense"></i>
		                                    </form>

		                                    
		                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $hod->id }}">
		                                        <div class="modal-dialog modal-dialog-centered">
		                                            <div class="modal-content">
		                                                <div class="modal-header">
		                                                    <h5 class="modal-title">Delete</h5>
		                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
		                                                </div>
		                                                <div class="modal-body">
		                                                    <h4>Are you sure you want to delete this Hod ?</h4>
		                                                </div>
		                                                <div class="modal-footer">
		                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $hod->id }}').submit();">Yes, Delete</button>
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



		 		@can('hod-store')
				<!--*******************
					 Add Hod Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addHodModal">
					@include('dash.hods.create')
				  </div>
				<!--*******************
					 Add Hod Master End
		 		*****************-->
		 		@endcan

		 		@can('hod-update')
		 		<!--*******************
					 Edit Hod Master Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="EditHodModal">
					@include('dash.hods.edit')
				  </div>
				<!--*******************
					 Edit Hod Master End
		 		*****************-->
		 		@endcan
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->	
		

<script>
	let selectedHodId = null;

	function loadHod(HodId) {
	    selectedHodId = HodId; // Set the selected Hod ID globally
	    $.ajax({
	        url: "{{ url('hods') }}/" + HodId,
	        method: 'GET',
	        success: function (response) {
	            $('#name').val(response.name);
	            $('#email').val(response.email);
	            $('#department').val(response.department);
	            $('#EditHodModal').modal('show'); // Open the modal
	        },
	        error: function () {
	            alert('Error fetching Hod data.');
	        },
	    });
	}




// function UpdateHod(hodId) {
//     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

//     const data = {
//         name: document.getElementById('name').value,
//         email: document.getElementById('email').value,
//         department: document.getElementById('department').value,
//     };

//     fetch(`/hods/${hodId}`, {
//         method: 'PUT',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': csrfToken,
//         },
//         body: JSON.stringify(data),
//     })
//         .then((response) => {
//             if (!response.ok) {
//                 // If the response is not ok, throw an error
//                 return response.json().then((err) => {
//                     throw new Error(err.message || 'Failed to update Hod');
//                 });
//             }
//             return response.json();
//         })
//         .then((data) => {
//             // Success handler
//             alert(data.message); // Notify the user about the success
//             location.reload(); // Optionally reload the page or update the UI dynamically
//         })
//         .catch((error) => {
//             // Error handler
//             alert(`Error updating Hod: ${error.message}`);
//         });
// }

function UpdateHod(hodId) {
	    const companyData = {
	        name: $('#name').val(),
	        email: $('#email').val(),
	        department: $('#department').val(),
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('hods') }}/" + hodId, {
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
	                $('#EditHodModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error updating hod');
	        });
	}
function CreateHod() {
	    const companyData = {
	        name: $('#names').val(),
	        email: $('#emails').val(),
	        department: $('#departments').val(),
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ route('hods.store') }}", {
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
	                $('#addHodModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error creating hod');
	        });
	}
</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
