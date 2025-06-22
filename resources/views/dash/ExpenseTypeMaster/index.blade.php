@extends('layouts.main')
@section('title', 'Expense Type Master | Jmitra & Co. Pvt. Ltd')
@section('content')
@can('expense-type-list')	
		<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('expense-type-create')
			<!-- <div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addExpenseTypeModal"><i class="fa-solid fa-plus me-1"></i> Add Expense Type</button>
				</div>
			</div> -->
			@endcan

			@can('expense-type-search')
				@include('includes.search')
			@endcan

			@can('expense-type-list')
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Expense Type Master List</h5>
					</div>
					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
					              <th scope="col" style="width: 10%;">S.No. </th>
							      <th scope="col" style="width: 10%;">Expense Type </th>
							      <!-- <th scope="col" style="width: 10%;">Action </th>						               -->
					                
					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
						  	@foreach ($expense_type as $key => $single_expense_type)
                              <tr>
                                <td>{{ ++$i }}</td>                                
                                <td>{{ $single_expense_type->expense_type }}</td>                                  
                                <!-- <td>
                                    <div class="d-flex">
                                    @can('expense-type-edit')
                                     <span>
                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateExpenseTypeModal" 
                                               onclick="loadExpenseType('{{ $single_expense_type->id }}', 'edit')">
						      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
						      			</a>
						      		</span>                                       
                                    @endcan                                                


                                    @can('expense-type-delete')
                                    <form method="POST" action="{{ route('expense_type.destroy', $single_expense_type->id) }}" style="display:inline" id="deleteForm-{{ $single_expense_type->id }}">
                                        @csrf
                                        @method('DELETE')
                                        
                                       
                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $single_expense_type->id }}" 
                                           title="Delete Expense
                                           Type"></i>
                                    </form>

                                    
                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $single_expense_type->id }}">
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
                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $single_expense_type->id }}').submit();">Yes, Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                @endcan                             
                                </div>                                   
                                </td> -->
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


		 		@can('expense-type-store')
				<!--*******************
					 Add Expense Type Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addExpenseTypeModal">
				 	@include('dash.ExpenseTypeMaster.create')					
				  </div>				  
				<!--*******************
					 Add Expense Type End
		 		*****************-->
		 		@endcan

		 		@can('expense-type-update')
		 		<!--*******************
					 Edit Expense Type Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="updateExpenseTypeModal">
					@include('dash.ExpenseTypeMaster.edit')
				  </div>
				<!--*******************
					 Edit Expense Type End
		 		*****************-->
		 		@endcan

			</div>
			@endcan
		</div>
		<!--*******************
			Main Content End
		 *****************-->

<script>
	let selectedExpenseTypeId = null;
    function loadExpenseType(ExpenseTypeId) {
    	 selectedExpenseTypeId = ExpenseTypeId; // Save the selected Expense Type ID
    	$.ajax({
            url: "{{ url('expense_type') }}/" + ExpenseTypeId,  // Make sure this URL is correct
            method: 'GET',
            success: function(response) {
                // Populate modal with user data
                $('#expense_type').val(response.expense_type);
                // Show the modal
                $('#updateExpenseTypeModal').modal('show');
            },
            error: function() {
                alert('Error fetching Expense Type details.');
            }
        });        
    }



    function updateExpenseType(ExpenseTypeId) {
	    const expense_typeData = {
	        expense_type: $('#expense_type').val(),
	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('expense_type') }}/" + ExpenseTypeId, {
	        method: 'PUT',
	        headers: {
	            'Content-Type': 'application/json',
	            'X-CSRF-TOKEN': token,
	        },
	        body: JSON.stringify(expense_typeData),
	    })
	        .then(response => response.json())
	        .then(data => {
	            if (data.error) {
	                alert(data.error);
	            } else {
	                alert(data.message);
	                $('#updateExpenseTypeModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error Expense Type');
	        });
	}

	function CreateExpenseType() {
	    const expense_typeData = {
	        expense_type: $('#expense_types').val(),	        
	    };
	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	    fetch("{{ route('expense_type.store') }}", {
	        method: 'POST',
	        headers: {
	            'Content-Type': 'application/json',
	            'X-CSRF-TOKEN': token,
	        },
	        body: JSON.stringify(expense_typeData),
	    })
	        .then(response => response.json())
	        .then(data => {
	            if (data.error) {
	                alert(data.error);
	            } else {
	                alert(data.message);
	                $('#addExpenseTypeModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error creating Expense Type');
	        });
	}
</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
