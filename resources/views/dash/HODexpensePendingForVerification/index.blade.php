@extends('layouts.main')
@section('title', 'HOD Exprense Pending For Verification| Jmitra & Co. Pvt. Ltd')
@section('content')
@can('monthly-pending-expense-list')
<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			@can('monthly-pending-expense-list')
				@include('includes.search')
			@endcan

			@include('includes.message')
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">{{ $title }}</h5>
					</div>
					<div class="table-responsive">
						<table id="example" class="table bg-white rounded shadow-sm table-hover">
						  <thead>
						    <tr>
						      <th scope="col">S.No.</th>
						      <th scope="col">Employee ID</th>
						      <th scope="col">Employee Name</th>
						      <th scope="col">Expense ID</th>
						      <th scope="col">Designation</th>
						      <th scope="col">Company Name</th>
						      <th scope="col">Month</th>
						      <th scope="col">Status</th>
						      <th scope="col">Action</th>
						      <th scope="col">Final Print</th>
						    </tr>
						  </thead>
						  <tbody>
						  	
					        @foreach ($pending_monthly_expenses as $key => $pending_monthly_expenses)
						    <tr>
						      <td scope="row">{{ ++$key }}</td>
						      <td>{{ $pending_monthly_expenses->user->userDetail->employee_id }}</td>
						      <td class="text-primary fw-bold text-capitalize">{{ $pending_monthly_expenses->user->name }}</td>
						      <td>{{ $pending_monthly_expenses->expense_id }}</td>
						      <td>{{ $pending_monthly_expenses->user->userDetail->designation->name }}</td>
						      <td>{{ $pending_monthly_expenses->user->userDetail->companyMaster->company_name }}</td>
						      <td>{{ \Carbon\Carbon::parse($pending_monthly_expenses->expense_date)->format('F') }}</td>
						      <td>
						      		@if ($pending_monthly_expenses->status == 3)
								        <span class="text-danger">Rejected</span>
								    @elseif ($pending_monthly_expenses->status == 2)
								        <span class="text-danger">Pending</span>
								    @elseif ($pending_monthly_expenses->status == 1)
								        <span class="text-success">Completed</span>
								    @elseif ($pending_monthly_expenses->status == 0)
								        <span class="text-info">Not Submitted</span>
								    @endif
						      	
						  	  </td>
						      <td>
						      	<div class="d-flex">
						      		@can('monthly-pending-expense-edit')
						      		<span>
						      			<a href="{{ route('pending_expense_verification.edit', $pending_monthly_expenses->id) }}"><i class="fa-solid fa-pen bg-success text-white p-1 rounded-circle shadow me-2"></i>
						      			</a>
						      		</span>
						      		@endcan                               

						      	</div>
						      </td>
							  <td>
							  	<a href="{{ route('pending_expense_verification_print', $pending_monthly_expenses->id) }}"><button type="button" class="btn btn-warning text-white">Print</button></a>
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
		 		<!--*******************
					   Final Print Start
				*****************-->
				<div class="modal" tabindex="-1" id="printModal">
					<div class="modal-dialog modal-dialog-centered modal-fullscreen">
					  <div class="modal-content">
						<div class="modal-header d-flex justify-content-between">
						  <h5 class="">Final Print</h5>
						  <button type="button" class="btn btn-success"><i class="fa-solid fa-download"></i></button>
						</div>
						<div class="modal-body">
							<div class="row mb-4">
								<div class="col-lg-4 col-12">
									Compnay Name: xyz
								</div>
								<div class="col-lg-4 col-12">
									Division: xyz
								</div>
								<div class="col-lg-4 col-12">
									Month: xyz
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-lg-4 col-12">
									Name: xyz
								</div>
								<div class="col-lg-4 col-12">
									Designation: xyz
								</div>
								<div class="col-lg-4 col-12">
									Location: xyz
								</div>
							</div>
							<div class="row mb-4">
								<table class="table table-bordered table-hover text-center table-light">
									<tbody>
										<tr class="table-active fw-bold">
											<td rowspan="2">Date</td>
											<td rowspan="2">From</td>
											<td rowspan="2">To</td>
											<td colspan="2">Time</td>
											<td rowspan="2">KM</td>
											<td rowspan="2">KM as per Google</td>
											<td rowspan="2">Mode</td>
											<td rowspan="2">Fare Amount</td>
											<td colspan="2">Daily Allowances</td>
											<td rowspan="2">Postage</td>
											<td rowspan="2">TEL/TGM</td>
											<td colspan="2">Other Expenses</td>
											<td rowspan="2">Attendace</td>
										</tr>
										<tr class="table-active fw-bold">
											<td>DEP</td>
											<td>ARR</td>
											<td>Working</td>
											<td>N. Working</td>
											<td>Purpose</td>
											<td>Amount</td>
										</tr>
										<tr>
											<td>1/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>500</td>
											<td></td>
											<td></td>
											<td></td>
											<td>500</td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>2/8/24</td>
											<td>delhi</td>
											<td></td>
											<td>Lalsot, Bari</td>
											<td></td>
											<td></td>
											<td>250</td>
											<td>1000</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>Cormier</td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>3/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>To</td>
											<td></td>
											<td></td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>4/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>1860</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>5/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>380</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>Working with<br>summer sir</td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>6/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>380</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>7/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>380</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>8/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>380</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>9/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>380</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>10/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>380</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td>11/8/24</td>
											<td>delhi</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>380</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>500</td>
											<td class="table-active">View</td>
										</tr>
										<tr>
											<td colspan="8"></td>
											<td>Total</td>
											<td>***</td>
											<td>***</td>
											<td>***</td>
											<td>***</td>
											<td>***</td>
											<td>***</td>
											<td>***</td>
										</tr>
										<tr>
											<td colspan="7"></td>
											<td>RUPEES in Words</td>
											<td colspan="4">***</td>
											<td>Grand Total</td>
											<td colspan="3">***</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row mb-4">
								<div class="col-lg-6 col-12 fw-bold">Signature of Field Staff: __________ __________ __________</div>
								<div class="col-lg-6 col-12 fw-bold">Signature of Manager: __________ __________ __________</div>
							</div>
							<div class="row mb-4">
								<div class="form-check">
									<input class="form-check-input me-2 ms-1" type="checkbox" value="" id="flexCheckIndeterminateAgree">
									<label class="form-check-label text-muted" for="flexCheckIndeterminateAgree">
									  I hereby confirmed that I verified the Expenses and found OK as per Travel/ Daily Allowance Policy.
									</label>
								  </div>
							</div>
							<div class="row mb-4">
								<div class="col-lg-4 col-12">Submitted by<br>(employee name)<br>DD-MM-YY<br>HH-MM AM/PM</div>
								<div class="col-lg-4 col-12">Verified by<br>(employee name)<br>DD-MM-YY<br>HH-MM AM/PM</div>
								<div class="col-lg-4 col-12">Approved by<br>(employee name)<br>DD-MM-YY<br>HH-MM AM/PM</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success">Final Print</button>
							<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Closed</button>
						</div>
					  </div>
					</div>
				  </div>
				<!--*******************
					   Final Print End
				*****************-->
				
				<!--*******************
					   Reason of Rejected Start
				*****************-->
				<div class="modal" tabindex="-1" id="reasonOfRejectedModal">
					<div class="modal-dialog modal-dialog-centered">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Reason of Rejected</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<label for="">Reason of Rejected</label>
							<select class="form-select" aria-label="Default select example">
								<option selected>Reason of Rejected</option>
								<option value="1">Reason of Rejected 1</option>
								<option value="2">Reason of Rejected 2</option>
								<option value="3">Reason of Rejected 3</option>
							  </select>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-info text-white">Save</button>
						  <button type="button" class="btn btn-success">Submit</button>
						</div>
					  </div>
					</div>
				  </div>
				<!--*******************
					   Reason of Rejected End
				*****************-->
				<!--*******************
					   Reason of Re-Open Start
				*****************-->
				<div class="modal" tabindex="-1" id="reasonOfReOpenModal">
					<div class="modal-dialog modal-dialog-centered">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Reason of Re-Open</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<label for="">Reason of Re-Open</label>
							<select class="form-select" aria-label="Default select example">
								<option selected>Reason of Re-Open</option>
								<option value="1">Reason of Re-Open 1</option>
								<option value="2">Reason of Re-Open 2</option>
								<option value="3">Reason of Re-Open 3</option>
							  </select>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-info text-white">Save</button>
						  <button type="button" class="btn btn-success">Submit</button>
						</div>
					  </div>
					</div>
				  </div>
				<!--*******************
					   Reason of Re-Open End
				*****************-->
				<!--*******************
					    Submission Promisiong Start
				*****************-->
				<div class="modal" tabindex="-1" id="submissionPromisingModal">
					<div class="modal-dialog modal-dialog-centered">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Reason of Re-Open</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<h6>Please Re-Check your work before Final Submission. After Final Submission you will not be able to modify it.</h6>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-success">Final Submit</button>
						</div>
					  </div>
					</div>
				  </div>
				<!--*******************
					   Submission Promisiong End
				*****************-->
				<!--*******************
					    View of Attendance Start
				*****************-->
				<div class="modal" tabindex="-1" id="viewOfAttendanceModal">
					<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">View of Attendance</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<table class="table table-bordered table-hover text-center table-light">
								<tbody>
									<tr class="table-active fw-bold">
										<td>Name</td>
										<td>Check In Time</td>
										<td>Check Out Time</td>
										<td>Joint Purpose Details</td>
										<td>Duration</td>
										<td>Check In Address</td>
									</tr>
									<tr>
										<td>xyz</td>
										<td>18 May, 2024 05:53 PM</td>
										<td>18 May, 2024 05:53 PM</td>
										<td>Follow up call for<br>DD1::Product Promotion</td>
										<td>21 days 2.0 hrs 44 min</td>
										<td>1-Abhyankar Road, 305</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Closed</button>
						</div>
					  </div>
					</div>
				  </div>
				<!--*******************
					   View of Attendance End
				*****************-->
			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->
		

@else
@include('forbidden.forbidden')
@endcan
@endsection

@section('additional_script')

@endsection