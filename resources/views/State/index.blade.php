@extends('layouts.main')
@section('title', 'State Master | Jmitra & Co. Pvt. Ltd')
@section('content')
    @can('state-list')
        <!--*******************
                           Main Content start
                          *******************-->
        <div class="container-fluid px-4">
            @can('state-create')
                <div class="row g-3 my-2">
                    <div class="d-grid d-flex justify-content-end">
                        <button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addState"><i
                                class="fa-solid fa-plus me-1"></i> Add State</button>
                    </div>
                </div>
            @endcan
            @can('state-search')
                @include('includes.search')
            @endcan
            @can('state-list')
                <div class="row my-5">
                    <div class="col">
                        <div class="border table-header p-4 position-relative rounded-top-4">
                            <h5 class="text-white">State List</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table bg-white rounded shadow-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No.</th>
                                        <th scope="col">State Name</th>
                                        <th scope="col">State In Short</th>
                                        <th scope="col">Zone Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp
                                    @foreach ($states as $key => $state)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $state->name }}</td>
                                            <td>{{ $state->short }}</td>
                                            <td>{{ $state->Zone?->zone_name ?? '' }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('state-edit')
                                                        <span>
                                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                                data-bs-target="#EditState"
                                                                onclick="loadState('{{ $state->id }}', 'edit')">
                                                                <i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2"
                                                                    data-bs-toggle="modal"></i>
                                                            </a>
                                                        </span>
                                                    @endcan


                                                    @can('state-delete')
                                                        <!-- <form method="POST" action="{{ route('state.destroy', $state->id) }}" style="display:inline" id="deleteForm-{{ $state->id }}">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        
                                                                                        
                                                                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2"
                                                                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $state->id }}"
                                                                                           title="Delete state"></i>
                                                                                    </form>

                                                                                   
                                                                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $state->id }}">
                                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title">Delete</h5>
                                                                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <h4>Are you sure you want to delete this state?</h4>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $state->id }}').submit();">Yes, Delete</button>
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


                    @can('state-store')
                        <!--*******************
                                                     Add State Master Start
                                                  *****************-->
                        <div class="modal" tabindex="-1" id="addState">
                            @include('State.create')
                        </div>
                        <!--*******************
                                                     Add State Master End
                                                  *****************-->
                    @endcan

                    @can('state-update')
                        <!--*******************
                                                     Edit State Master Start
                                                  *****************-->
                        <div class="modal" tabindex="-1" id="EditState">
                            @include('State.edit')
                        </div>
                        <!--*******************
                                                     Edit State Master End
                                                  *****************-->
                    @endcan


                </div>
            @endcan
        </div>
        <!--*******************
                           Main Content End
                          *****************-->

        <script>
            // let selectedStateId = null;
            // function loadState(stateId) {
            // 	 selectedStateId = stateId; // Save the selected State ID
            // 	$.ajax({
            //         url: "{{ url('state') }}/" + stateId,  // Make sure this URL is correct
            //         method: 'GET',
            //         success: function(response) {
            //             // Populate modal with user data
            //             $('#name').val(response.name);
            //             $('#short').val(response.short);
            //             // Show the modal
            //             $('#EditState').modal('show');
            //         },
            //         error: function() {
            //             alert('Error fetching State details.');
            //         }
            //     });        
            // }
            let selectedStateId = null;

            function loadState(stateId) {
                selectedStateId = stateId;

                // Clear previous values (guaranteed clean-up)
                $('#EditState input, #EditState select').each(function() {
                    $(this).val('').prop('selected', false);
                });

                $.ajax({
                    url: "{{ url('state') }}/" + stateId,
                    method: 'GET',
                    success: function(response) {
                        // Populate modal fields with new state data
                        $('#name').val(response.name);
                        $('#short').val(response.short);

                        // Set zone_id properly (handle if value mismatch)
                        $('#zone_ide').val(response.zone_id);
                        $('#zone_ide option').prop('selected', false); // clear all selected
                        $('#zone_ide option').each(function() {
                            if ($(this).val() == response.zone_id) {
                                $(this).prop('selected', true);
                            }
                        });

                        // Show the modal
                        $('#EditState').modal('show');
                    },
                    error: function() {
                        alert('Error fetching State details.');
                    }
                });
            }




            function UpdateState(stateId) {   
                const stateData = {
                    name: $('#name').val(),
                    short: $('#short').val(),
					zone_id: $('#zone_ide option:selected').val()

                };

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch("{{ url('state') }}/" + stateId, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify(stateData),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            alert(data.message);
                            $('#EditState').modal('hide');
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating State');
                    });
            }

            function CreateState() {
                const stateData = {
                    state_name: $('#state_name').val(),
                    short_name: $('#short_name').val(),
                    zone_id: $('#zone_id').val(),
                };

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch("{{ route('state.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify(stateData),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            alert(data.message);
                            $('#addState').modal('hide');
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error creating State');
                    });
            }
        </script>
    @else
        @include('forbidden.forbidden')

    @endcan
@endsection
