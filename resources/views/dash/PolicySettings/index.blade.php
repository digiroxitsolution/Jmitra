@extends('layouts.main')
@section('title', 'Policy settings | Jmitra & Co. Pvt. Ltd')
@section('content')	
@can('policy-setting-list')
<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					@can('policy-guidelines-list')
						<a href="{{ route('policy_guidelines.index') }}" ><button class="btn add me-3 shadow" type="button">Policy Guidelines</button></a>
					@endcan

					@can('policy-setting-create')
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addModal">Add New Policy Setting</button>
					@endcan
				</div>
			</div>
			@include('includes.search')


			@include('includes.message')
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Policy's Setting</h5>
					</div>
					<div class="table-responsive">
						<table id="example" class="table bg-white rounded shadow-sm table-hover">
						  <thead>
						    <tr>
						      <th scope="col">S.No. </th>
						      <th scope="col">Policy ID </th>
						      <th scope="col">Name </th>
						      <th scope="col">Desginations </th>
						      <th scope="col">Creation Date </th>
						      <th scope="col">Effective Date </th>
						      <th scope="col">Modified Date </th>
						      <th scope="col">Action </th>
						    </tr>
						  </thead>
						  <tbody>
						     @foreach ($policySettings as $index => $policy)
						        <tr>
						            <td scope="row">{{ $index + 1 }}</td>
						            <td>{{ $policy->policy_id }}</td>
						            <td>{{ $policy->policy_name }}</td>
						            <td>{{ $policy->designation->name }}</td>
						            <td>{{ $policy->created_at->format('d-m-Y') }}</td>
						            <td>{{ \Carbon\Carbon::parse($policy->effective_date)->format('d-m-Y') }}</td>
						            <td>{{ $policy->updated_at->format('d-m-Y') }}</td>
						            <td>
	                                    <div class="d-flex">
	                                    	
								      		
	                                    @can('policy-setting-edit')
	                                    <span>                         	
							      		
								      		<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editPolicyModal{{ $policy->id }}">
											    <i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2"></i>
											</a>
										</span> 
	                                    @endcan                                                


	                                    @can('policy-setting-delete')
	                                    <!-- <form method="POST" action="{{ route('policy_settings.destroy', $policy->id) }}" style="display:inline" id="deleteForm-{{ $policy->id }}">
	                                        @csrf
	                                        @method('DELETE')
	                                        
	                                       
	                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
	                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $policy->id }}" 
	                                           title="Delete mode of expense"></i>
	                                    </form>

	                                    
	                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $policy->id }}">
	                                        <div class="modal-dialog modal-dialog-centered">
	                                            <div class="modal-content">
	                                                <div class="modal-header">
	                                                    <h5 class="modal-title">Delete</h5>
	                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
	                                                </div>
	                                                <div class="modal-body">
	                                                    <h4>Are you sure you want to delete this Policy settings ?</h4>
	                                                </div>
	                                                <div class="modal-footer">
	                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $policy->id }}').submit();">Yes, Delete</button>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div> -->
	                                @endcan
	                                @can('policy-setting-view')
	                                	<span>                                        
                                            <!-- Trigger Modal with Dynamic Data -->
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewModal" 
                                               onclick="loadPolicyDetails('{{ $policy->id }}')">
                                                <i class="fa-solid fa-eye bg-success text-white p-1 rounded-circle shadow" title="View Settings"></i>
                                            </a>
                                        </span>   
                                        @endcan                   
	                                </div>                                   
	                                </td>
						        </tr>
						       
						       

						 		
						 		
						    @endforeach
						  </tbody>
						</table>
					</div>
				</div>

				@foreach ($policySettings as $index => $policy)
				@can('policy-setting-update')
				<!--*******************
						Edit Modal Start
				*****************-->
				@include('dash.PolicySettings.edit')
				<!--*******************
					Edit Modal End
				*****************-->
				@endcan
				@endforeach

				<!--*******************
					 Pagination Start
		 		*****************-->
				@include('includes.pagination')
				<!--*******************
					 Pagination End
		 		*****************-->

		 					@can('policy-setting-store')
								
						 		@include('dash.PolicySettings.create')
						 		
						 	@endcan
                  



		 		@can('policy-setting-view')
		 		<!--*******************
                     View Modal Start
                *****************-->
                <!-- View Modal -->
                	@include('dash.PolicySettings.view')

                <!--*******************
                     View Modal End
                *****************-->
                @endcan
		 		
		 		
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->
<script type="text/javascript">
function loadPolicyDetails(PolicyDetailId) {
        $.ajax({
            url: '/policy_settings/' + PolicyDetailId,  // Dynamically pass the userId
            method: 'GET',
            success: function(response) {
			    console.log(response); // Debugging the response structure

			    $('#policy_ids').text(response.policy_ids);
			    $('#policy_names').text(response.policy_names);
			    $('#designations').text(response.designations ? response.designations.name : 'N/A');
			    $('#location_das').text(response.location_das);
			    $('#ex_location_das').text(response.ex_location_das);
			    $('#outstation_das').text(response.outstation_das);
			    $('#intercity_travel_ex_locations').text(response.intercity_travel_ex_locations);
			    $('#intercity_travel_outstations').text(response.intercity_travel_outstations);
			    $('#two_wheelers_charges').text(response.two_wheelers_charges);
			    $('#four_wheelers_charges').text(response.four_wheelers_charges);	    
			    $('#others').text(response.others);

			    $('#viewModal').modal('show');
			},
            error: function() {
                alert('Error fetching Policy details.');
            }
        });
    }


	document.getElementById('policyForm').addEventListener('submit', function(event) {
    let isValid = true;

    // Check if all required fields are filled
    const fields = [
        { id: 'policy_name', errorId: 'policyNameError' },
        { id: 'designation_id', errorId: 'designationError' },
        { id: 'location_da', errorId: 'locationDAError' },
        { id: 'ex_location_da', errorId: 'exLocationDAError' },
        { id: 'outstation_da', errorId: 'outstationDAError' },
        { id: 'intercity_travel_ex_location', errorId: 'intercityExLocationError' },
        { id: 'intercity_travel_outstation', errorId: 'intercityOutstationError' }
    ];

    fields.forEach(field => {
        const input = document.getElementById(field.id);
        const errorMessage = document.getElementById(field.errorId);

        if (!input.value) {
            errorMessage.style.display = 'block';
            isValid = false;
        } else {
            errorMessage.style.display = 'none';
        }
    });

    // Prevent form submission if validation fails
    if (!isValid) {
        event.preventDefault();
    }
});

</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
