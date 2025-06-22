@extends('layouts.main')
@section('title', 'Policy Guidelines | Jmitra & Co. Pvt. Ltd')
@section('content')    

@can('policy-guidelines-list')
<!--*******************
    Main Content start
*******************-->
<div class="container-fluid px-4">
    <div class="row g-3 my-2">
        <div class="d-grid d-flex justify-content-end">
            @can('policy-guidelines-create')
                <a href="{{ route('policy_settings.index') }}" ><button class="btn add me-3 shadow" type="button">Policy Setting</button></a>
            @endcan

            @can('policy-guidelines-create')
            <button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addModal">Add New Policy Guidelines</button>
            @endcan

        </div>
    </div>
    @include('includes.search')

    @include('includes.message')
    <div class="row my-5">
        <div class="col">
            <div class="border table-header p-4 position-relative rounded-top-4">
                <h5 class="text-white">Policy's Guidelines</h5>
            </div>
            <div class="table-responsive">
                <table id="example" class="table bg-white rounded shadow-sm table-hover">
                    <thead>
                        <tr>
                            <th scope="col">S.No.</th>
                            <th scope="col">Policy ID</th>
                            <th scope="col">Policy Name</th>
                            <th scope="col">Creation Date</th>
                            <th scope="col">Effective Date</th>
                            <th scope="col">Modified Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($policyGuidelines as $index => $guidelines)
                            <tr>
                                <td scope="row">{{ $index + 1 }}</td>

                                    <td>{{ $guidelines->PolicySettings->policy_id }}</td>
                                    <td>{{ $guidelines->PolicySettings->policy_name }}</td>
                                    <td>{{ $guidelines->created_at->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($guidelines->PolicySettings->effective_date)->format('d-m-Y') }}</td>
                                    <td>{{ $guidelines->updated_at->format('d-m-Y') }}</td>
                                    <td>
                                    <div class="d-flex">
                                        @can('policy-guidelines-edit')
                                        <span>
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal{{ $guidelines->id }}">
                                                <i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2"></i>
                                            </a>                                      
                                        </span> 
                                        @endcan

                                        @can('policy-guidelines-delete')
                                        <!-- <form method="POST" action="{{ route('policy_guidelines.destroy', $guidelines->id) }}" style="display:inline" id="deleteForm-{{ $guidelines->id }}">
                                            @csrf
                                            @method('DELETE')
                                            
                                            
                                            <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
                                               style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $guidelines->id }}" 
                                               title="Delete mode of expense"></i>
                                        </form>

                                        
                                        <div class="modal" tabindex="-1" id="deleteModal-{{ $guidelines->id }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete</h5>
                                                        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h4>Are you sure you want to delete this Policy guidelines ?</h4>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $guidelines->id }}').submit();">Yes, Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        @endcan

                                        @can('policy-guidelines-show')
                                        <!-- <span>                                        
                                            
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewModal" 
                                               onclick="loadPolicyGuidelines('{{ $guidelines->id }}')">
                                                <i class="fa-solid fa-eye bg-success text-white p-1 rounded-circle shadow" title="View guidelines"></i>
                                            </a>
                                        </span>   -->
                                        @endcan 
                                                          
                                    </div>                                   
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @foreach ($policyGuidelines as $index => $guidelines)
         @can('policy-guidelines-update')
         <!--*******************
                Edit Modal Start
         *****************-->
         @include('dash.PolicyGuidelines.edit')
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
         

                @can('policy-guidelines-store')               
                <!--*******************
                     Add Modal Start
                *****************-->
                    @include('dash.PolicyGuidelines.create')
                <!--*******************
                     Add Modal End
                *****************-->
                @endcan

                @can('policy-guidelines-show')
                <!--*******************
                     View Modal Start
                *****************-->
                <!-- View Modal -->
                    @include('dash.PolicyGuidelines.view')

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
function loadPolicyGuidelines(PolicyDetailId) {
    $.ajax({
        url: '/policy_guidelines/' + PolicyDetailId,
        method: 'GET',
        success: function(response) {
            // Check if data is available before updating the modal
            if (response.policy_setting_id && response.policy_descriptions) {
                // Populate modal content with the response
                // $('#policy_id').text(response.designations ? response.policy_id.name : 'N/A');
                $('#policy_setting_id').text(response.policy_setting_id);
                $('#policy_descriptions').text(response.policy_descriptions);
                $('#file_names').text(response.file_names);
                $('#uploaded_files').text(response.uploaded_files);

                // Show the modal after loading content
                $('#viewModal').modal('show');
            } else {
                alert('Data is missing.');
            }
        },
        error: function() {
            alert('Error fetching Policy Guidelines.');
        }
    });
}



    document.getElementById('guidelineForm').addEventListener('submit', function(event) {
    let isValid = true;

    // Check if all required fields are filled
    const fields = [
        { id: 'file_name', errorId: 'file_nameError' },
        { id: 'policy_setting_id', errorId: 'policy_setting_idError' },
        { id: 'policy_description', errorId: 'policy_descriptionError' },
        
        
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
