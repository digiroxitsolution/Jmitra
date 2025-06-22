@extends('layouts.main')
@section('title', 'Mode Of Expense Master | Jmitra & Co. Pvt. Ltd')
@section('content')
@can('mode-of-expense-list')
	
		<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('mode-of-expense-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addExpenseModal"><i class="fa-solid fa-plus me-1"></i> Add Mode of Expenses</button>
				</div>
			</div>
			@endcan
			@can('mode-of-expense-search')
				@include('includes.search')
			@endcan
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Mode of Expense Master List</h5>
					</div>
					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
						            <th scope="col" style="width: 10%;">S.No.</th>
							      	<th scope="col" style="width: 10%;">Mode Expense</th>
							      	<th scope="col" style="width: 10%;">Action</th>					            
					                
					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
								  	@foreach ($mode_of_expenses as $key => $mode_of_expense)
		                              <tr>
		                                <td>{{ ++$i }}</td>                                
		                                <td>{{ $mode_of_expense->mode_expense }}</td>                                  
		                                <td>
		                                    <div class="d-flex">
		                                    @can('mode-of-expense-edit')
		                                    <span>
		                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#EditExpenseModal" 
		                                               onclick="loadModeOfExpenses('{{ $mode_of_expense->id }}', 'edit')">
								      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
								      			</a>
								      		</span>                                        
		                                    @endcan                                                


		                                    @can('mode-of-expense-delete')
		                                    <!-- <form method="POST" action="{{ route('mode_of_expense_master.destroy', $mode_of_expense->id) }}" style="display:inline" id="deleteForm-{{ $mode_of_expense->id }}">
		                                        @csrf
		                                        @method('DELETE')
		                                        
		                                        
		                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
		                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $mode_of_expense->id }}" 
		                                           title="Delete mode of expense"></i>
		                                    </form>

		                                    
		                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $mode_of_expense->id }}">
		                                        <div class="modal-dialog modal-dialog-centered">
		                                            <div class="modal-content">
		                                                <div class="modal-header">
		                                                    <h5 class="modal-title">Delete</h5>
		                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
		                                                </div>
		                                                <div class="modal-body">
		                                                    <h4>Are you sure you want to delete this Rejected Reason ?</h4>
		                                                </div>
		                                                <div class="modal-footer">
		                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $mode_of_expense->id }}').submit();">Yes, Delete</button>
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


		 		@can('mode-of-expense-delete')
				<!--*******************
					 Add and Edit Expense Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addExpenseModal">
					@include('dash.ModeofExpenseMaster.create')
				  </div>
				<!--*******************
					 Add and Edit Expense End
		 		*****************-->
		 		@endcan
		 		@can('mode-of-expense-delete')
		 		<!--*******************
					 Add and Edit Expense Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="EditExpenseModal">
					@include('dash.ModeofExpenseMaster.edit')
				  </div>
				<!--*******************
					 Add and Edit Expense End
		 		*****************-->
		 		@endcan
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->
	
		

<script>
	let selectedModeOfExpensesId = null;
    function loadModeOfExpenses(ModeOfExpensesId) {
    	 selectedModeOfExpensesId = ModeOfExpensesId; // Save the selected Divison ID
    	$.ajax({
            url: "{{ url('mode_of_expense_master') }}/" + ModeOfExpensesId,  // Make sure this URL is correct
            method: 'GET',
            success: function(response) {
                // Populate modal with user data
                $('#mode_expenses').val(response.mode_expense);
                // Show the modal
                $('#EditExpenseModal').modal('show');
            },
            error: function() {
                alert('Error fetching Mode Of Expenses.');
            }
        });        
    }



function UpdateModeOfExpenses(ModeOfExpensesId) {
	    const companyData = {
	        mode_expense: $('#mode_expenses').val(),
	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('mode_of_expense_master') }}/" + ModeOfExpensesId, {
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
	                $('#EditExpenseModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error updating mode of Expense');
	        });
	}


function CreateModeOfExpenses() {
	    const companyData = {
	        mode_expense: $('#mode_expense').val(),
	        
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ route('mode_of_expense_master.store') }}", {
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
	                $('#addExpenseModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error creating Mode Of Expenses');
	        });
	}


</script>

@else

@include('forbidden.forbidden')

@endcan
@endsection
