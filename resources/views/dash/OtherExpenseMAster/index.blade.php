@extends('layouts.main')
@section('title', 'Other Expense Master | Jmitra & Co. Pvt. Ltd')
@section('content')
@can('other-expense-list')
	
		<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('other-expense-create')
			<div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<button class="btn add shadow" type="button" data-bs-toggle="modal" data-bs-target="#addOtherExpenseModal"><i class="fa-solid fa-plus me-1"></i> Add Other Expense</button>
				</div>
			</div>
			@endcan
			@can('other-expense-search')
				@include('includes.search')
			@endcan
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Other Expense List</h5>
					</div>
					<div class="table-responsive">
					    <table id="example" class="table table-striped bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
					            <th scope="col" style="width: 10%;">S.No.</th>
						      	<th scope="col" style="width: 10%;">Other Expense</th>
						      	<th scope="col" style="width: 10%;">Action</th>				              
					                
					            </tr>
					        </thead>
					        <tbody>

					            @php $i = 0; @endphp
								  	@foreach ($other_expenses as $key => $other_expense)
		                              <tr>
		                                <td>{{ ++$i }}</td>                                
		                                <td>{{ $other_expense->other_expense }}</td>                                  
		                                <td>
		                                    <div class="d-flex">
		                                    @can('other-expense-edit')
		                                    <span>
		                                    	<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#EditOtherExpenseModal" 
		                                               onclick="loadOtherExpenses('{{ $other_expense->id }}', 'edit')">
								      			<i class="fa-solid fa-pen bg-info text-white p-1 rounded-circle shadow me-2" data-bs-toggle="modal"></i>
								      			</a>
								      		</span>                                        
		                                    @endcan                                                


		                                    @can('other-expense-delete')
		                                    <!-- <form method="POST" action="{{ route('other_expense_master.destroy', $other_expense->id) }}" style="display:inline" id="deleteForm-{{ $other_expense->id }}">
		                                        @csrf
		                                        @method('DELETE')
		                                        
		                                        
		                                        <i class="fa-solid fa-trash-can bg-danger text-white p-1 rounded-circle shadow me-2" 
		                                           style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $other_expense->id }}" 
		                                           title="Delete Other Expense"></i>
		                                    </form>

		                                   
		                                    <div class="modal" tabindex="-1" id="deleteModal-{{ $other_expense->id }}">
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
		                                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm-{{ $other_expense->id }}').submit();">Yes, Delete</button>
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



		 		@can('other-expense-store')
				<!--*******************
					 Add Other Expense Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="addOtherExpenseModal">
					@include('dash.OtherExpenseMAster.create')
				  </div>
				<!--*******************
					 Add Other Expense End
		 		*****************-->
		 		@endcan
		 		@can('other-expense-update')
		 		<!--*******************
					 Edit Other Expense Start
		 		*****************-->
				 <div class="modal" tabindex="-1" id="EditOtherExpenseModal">
					@include('dash.OtherExpenseMAster.edit')
				  </div>
				<!--*******************
					 Edit Other Expense End
		 		*****************-->
		 		@endcan
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->
	
		

<script>
	let selectedOtherExpenseId = null;
    function loadOtherExpenses(OtherExpenseId) {
    	 selectedOtherExpenseId = OtherExpenseId; // Save the selected Divison ID
    	$.ajax({
            url: "{{ url('other_expense_master') }}/" + OtherExpenseId,  // Make sure this URL is correct
            method: 'GET',
            success: function(response) {
                // Populate modal with user data
                $('#other_expenses').val(response.other_expense);
                // Show the modal
                $('#EditOtherExpenseModal').modal('show');
            },
            error: function() {
                alert('Error fetching Other Expenses.');
            }
        });        
    }



// function UpdateOtherExpenses(OtherExpenseId) {
//     const other_expense = $('#other_expenses').val();
//     if (!other_expense.trim()) {
//         alert('Other Expense is required.');
//         return;
//     }

//     const data = { other_expense }; // Use shorthand syntax
//     const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

//     fetch("{{ url('other_expense_master') }}/" + OtherExpenseId, {
//         method: 'PUT',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': token,
//         },
//         body: JSON.stringify(data),
//     })
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Network response was not ok');
//             }
//             return response.json();
//         })
//         .then(data => {
//             console.log(data); // Log response for debugging
//             if (data.error) {
//                 alert(data.error);
//             } else {
//                 alert(data.message);
//                 $('#EditOtherExpenseModal').modal('hide');
//                 location.reload();  // Reload the page to reflect changes
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             alert('Error updating Other Expense');
//         });
// }
function UpdateOtherExpenses(OtherExpenseId) {
	    const companyData = {
	        other_expense: $('#other_expenses').val(),
	    };

	    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	    fetch("{{ url('other_expense_master') }}/" + OtherExpenseId, {
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
	                $('#EditOtherExpenseModal').modal('hide');
	                location.reload();
	            }
	        })
	        .catch(error => {
	            console.error('Error:', error);
	            alert('Error updating Other Expense');
	        });
	}


function CreateOtherExpenses() {
    const other_expense = $('#other_expense').val().trim();
    if (!other_expense) {
        alert('Other Expense is required.');
        return;
    }

    const data = { other_expense };
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch("{{ route('other_expense_master.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(error => {
                    throw new Error(error.error || 'Failed to create Other Expense');
                });
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            $('#addOtherExpenseModal').modal('hide');
            location.reload(); // Reload the page to fetch updated data
        })
        .catch(error => {
            console.error('Error:', error.message);
            alert('Error creating Other Expense: ' + error.message);
        });
}

</script>
@else

@include('forbidden.forbidden')

@endcan
@endsection
