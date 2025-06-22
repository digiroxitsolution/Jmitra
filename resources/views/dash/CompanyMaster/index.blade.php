@extends('layouts.main')
@section('title', 'Company Master | Jmitra & Co. Pvt. Ltd')
@section('content')	
@can('company-list')	
		<div class="container-fluid px-4">
			@can('company-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addAndViewCompanyModal"><i class="fa-solid fa-plus me-1"></i> Add Company</button>
				</div>
			</div>
			@endcan

			@can('company-search')
				@include('includes.search')
			@endcan

			
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Company Master List</h5>
					</div>
					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>					            	
					              <th scope="col" style="width: 10%;">S.No.</th>
							      <th scope="col" style="width: 40%;">Company Name</th>
							      <th scope="col" style="width: 20%;">Location</th>
								  <th scope="col" style="width: 20%;">Address</th>
							      <th scope="col" style="width: 25%;">Action</th>
					                
					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
								  	@foreach ($companies as $key => $company)
		                              <tr>
		                                <td>{{ ++$i }}</td>                                
		                                <td>{{ $company->company_name }}</td>                                
		                                <td>{{ $company->location }}</td>
		                                <td>{{ $company->address }}</td>                                  
		                                <td>
		                                    <div class="d-flex">
		                                    @can('company-edit')
		                                        <span>
		                                            <!-- Trigger Modal for Edit -->
		                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editCompanyModal" 
		                                               onclick="loadCompanyDetail('{{ $company->id }}', 'edit')">
		                                                <i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow" title="Edit Company"></i>
		                                            </a>
		                                        </span>
		                                    @endcan
		                                                                        


		                                    @can('company-delete')
		                                    <!-- <form method="POST" action="{{ route('company_master.destroy', $company->id) }}" style="display:inline" id="deleteForm-{{ $company->id }}">
		                                        @csrf
		                                        @method('DELETE')
		                                        
		                                        
		                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
		                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $company->id }}" 
		                                           title="Delete company"></i>
		                                    </form>

		                                   
		                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $company->id }}">
		                                        <div class="modal-dialog modal-dialog-centered">
		                                            <div class="modal-content">
		                                                <div class="modal-header">
		                                                    <h5 class="modal-title">Delete</h5>
		                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
		                                                </div>
		                                                <div class="modal-body">
		                                                    <h4>Are you sure you want to delete this company?</h4>
		                                                </div>
		                                                <div class="modal-footer">
		                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $company->id }}').submit();">Yes, Delete</button>
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
		 		@can('company-delete')
		 		<!--*******************
					 Delete Modal Start
		 		*****************-->
		 		<div class="modal" tabindex="-1" id="deleteModal">
				  <div class="modal-dialog modal-dialog-centered">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title">Delete</h5>
				        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
				      </div>
				      <div class="modal-body">
				      	<h4>Are you sure you want to delete?</h4>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary">Yes</button>
				      </div>
				    </div>
				  </div>
				</div>
				<!--*******************
					 Delete Modal End
		 		*****************-->
		 		@endcan
		 		@can('company-store')
				<!--*******************
					 Add  Company Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addAndViewCompanyModal">
				 	@include('dash.CompanyMaster.create')
				  </div>
				<!--*******************
					 Add Company End
		 		*****************-->
		 		@endcan


		 		@can('company-update')
		 		<!--*******************
					 Edit Company Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="editCompanyModal">
				 	@include('dash.CompanyMaster.edit')
				  </div>
				<!--*******************
					 Edit Company End
		 		*****************-->
		 		@endcan

			</div>
			


		</div>

<script>
	let selectedCompanyId = null;
    function loadCompanyDetail(companyId) {
    	 selectedCompanyId = companyId; // Save the selected company ID
    	$.ajax({
            url: "{{ url('company_master') }}/" + companyId,  // Make sure this URL is correct
            method: 'GET',
            success: function(response) {
                // Populate modal with user data
                $('#company_names').val(response.company_name);
                $('#locations').val(response.location);
                $('#addresss').val(response.address);
                
                // Show the modal
                $('#editCompanyModal').modal('show');
            },
            error: function() {
                alert('Error fetching Company details.');
            }
        });        
    }



    function UpdateCompany(companyId) {
	    const companyData = {
	        company_name: $('#company_names').val(),
	        location: $('#locations').val(),
	        address: $('#addresss').val(),
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('company_master') }}/" + companyId, {
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
	                $('#editCompanyModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error updating company');
	        });
	}

	function CreateCompany() {
	    const companyData = {
	        company_name: $('#company_name').val(),
	        location: $('#location').val(),
	        address: $('#address').val(),
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ route('company_master.store') }}", {
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
	                $('#addAndViewCompanyModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error creating company');
	        });
	}
</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
