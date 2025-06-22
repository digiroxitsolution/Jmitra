@extends('layouts.main')
@section('title', 'Users Master | Jmitra & Co. Pvt. Ltd')
@section('content')

@can('users-list')
        <!--*******************
            Main Content start
        *******************-->
        <div class="container-fluid px-4">
            <div class="row g-3 my-2">
                <div class="d-grid d-flex justify-content-end">
                    <button class="btn add me-3 shadow" type="button" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fa-solid fa-plus me-1"></i> Add New User</button>
                    <button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus me-1"></i> Upload Excel File</button>
                </div>
            </div>
            @can('users-search')
                @include('includes.search')
            @endcan
            
            @include('includes.message')
            <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                            <h5 class="text-white">Users List</h5>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table bg-white rounded shadow-sm table-hover">
                          <thead>
                            <tr>
                              <th scope="col">S.No. </th>
                              <th scope="col">Employee ID </th>
                              <th scope="col">Name </th>
                              <th scope="col">Role </th>
                              <th scope="col">E-Mail </th>
                              <th scope="col" style="width:100px;">HOD<br>Name </th>
                              <th scope="col">Company<br>Name </th>
                              <th scope="col">Location </th>
                              <th scope="col">Status </th>
                              <th scope="col">Action </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $key => $user)
                              <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $user->userDetail->employee_id ?? 'N/A' }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                  @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $role)
                                            <span class="badge text-bg-success">{{ $role }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->userDetail->hod->name ?? 'N/A' }}</td>
                                <td>{{ $user->userDetail->companyMaster->company_name ?? 'N/A' }}</td>
                                <td>{{ $user->userDetail->locationMaster->working_location ?? 'N/A' }}</td>
                                <td>
                                    @if ($user->status == 1)
                                        <span class="badge text-bg-success">Active</span>
                                    @elseif ($user->status == 2)
                                       <span class="badge text-bg-primary">Inactive</span>
                                    @elseif ($user->status == 3)
                                       <span class="badge text-bg-danger">Banned</span>
                                    @else
                                        <span class="badge text-bg-secondary">Unknown</span>
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="d-flex">
                                    @can('users-edit')
                                    @if ($user->status != 3)
                                        <span>
                                            <!-- Trigger Modal for Edit -->
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                                               onclick="loadUserDetail('{{ $user->id }}', 'edit')">
                                               <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">

                                                <i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow" title="Edit User"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @endcan
                                                                        


                                    @can('users-delete')
                                    <!-- <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline" id="deleteForm-{{ $user->id }}">
                                        @csrf
                                        @method('DELETE')
                                        
                                        
                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $user->id }}" 
                                           title="Delete User"></i>
                                    </form>

                                    
                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $user->id }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete</h5>
                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4>Are you sure you want to delete this user?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $user->id }}').submit();">Yes, Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                @endcan


                                    

                                    
                                    @can('users-show')
                                        <span>                                        
                                            <!-- Trigger Modal with Dynamic Data -->
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewModal" 
                                               onclick="loadUserDetails('{{ $user->id }}')">
                                                <i class="fa-solid fa-eye bg-success text-white p-1 rounded-circle shadow" title="View User"></i>
                                            </a>
                                        </span>
                                    @endcan
                                </div>
                                

                                 
                                   
                                    
                                </td>
                              </tr>
                             @endforeach

                             

                             
                            
                          </tbody>
                        </table>
                        @foreach ($data as $key => $user)
                             <!--*******************
                                     Edit Modal Start
                                *****************-->     
                                @can('users-update')
                                    @include('dash.users.edit')
                                @endcan
                                <!--*******************
                                    Edit Modal End
                                *****************--> 
                        @endforeach

                        @include('dash.users.upload')

                        <!--*******************
                                     View Modal Start
                                *****************-->
                                <!-- View Modal -->
                                    @can('users-show')
                                    @include('dash.users.show')
                                @endcan
                                <!--*******************
                                     View Modal End
                                *****************-->
                    </div>
                </div>
                <!--*******************
                     Pagination Start
                *****************-->
                    @include('includes.pagination')
                <!--*******************
                     Pagination End
                *****************-->
                <!--*******************
                     Add and Edit Modal Start
                *****************-->     
                @can('users-store')
                    @include('dash.users.create')
                @endcan
                <!--*******************
                     Add and Edit Modal End
                *****************-->

                
               
                
            </div>
        </div>
        <!--*******************
            Main Content End
         *****************-->
 <script>
    function loadUserDetails(userId) {
        $.ajax({
            url: '/users/' + userId,  // Dynamically pass the userId
            method: 'GET',
            success: function(response) {
                if (response.error) {
                    alert('User not found');
                    return;
                }
                // Populate modal with user data
                $('#employeeId').text(response.employee_id);
                $('#employeeName').text(response.name);
                $('#emails').text(response.emails);
                $('#designation').text(response.designation);
                // $('#status').text(response.status);
                // Update Status with a badge
                let statusBadge = '';
                if (response.statuss == 1) {
                    statusBadge = '<span class="badge text-bg-success">Active</span>';
                } else if (response.statuss == 2) {
                    statusBadge = '<span class="badge text-bg-primary">Inactive</span>';
                } else if (response.statuss == 3) {
                    statusBadge = '<span class="badge text-bg-danger">Banned</span>';
                } else {
                    statusBadge = '<span class="badge text-bg-secondary">Unknown</span>';
                }
                $('#statuss').html(statusBadge);  // Inject the badge into the status field
            

                $('#state').text(response.state);
                $('#city').text(response.city);
                $('#companyName').text(response.company_name);
                $('#workingLocation').text(response.working_location);
                $('#hodName').text(response.hod_name);
                $('#roles').html('<span class="badge text-bg-success">' + response.roles + '</span>');                 
            
                $('#policyApplicable').text(response.policy_applicable);
                
                // Show the modal
                $('#viewModal').modal('show');
            },
            error: function() {
                alert('Error fetching user details.');
            }
        });
    }


    



    


</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection

