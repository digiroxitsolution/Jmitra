						
						<table  id="example" class="table bg-white rounded shadow-sm table-hover">
						  <thead>
						    <tr>
						      <th scope="col">S.No.</th>
						      <th scope="col">Employee ID</th>
						      <th scope="col">Name</th>
						      <th scope="col">HOD Name</th>
						      <th scope="col">Company Name</th>
						      <th scope="col">Location</th>
						      <th scope="col">Expense ID</th>
						      <th scope="col">Month of Expense</th>
						      <th scope="col">Status</th>
						      <th scope="col">Submission Date</th>
						      
						    </tr>
						  </thead>
						  <tbody>
						  	@foreach ($sla_reports as $key => $sla_report)
						    <tr>
							      <td scope="col">{{ ++$key }}</td>
							      <td>{{ $sla_report->user->userDetail->employee_id ?? 'N/A' }}</td>
							        <td>{{ $sla_report->user->name ?? 'N/A' }}</td>

							        <td>{{ $sla_report->user->userDetail->hod->name ?? 'N/A' }}</td>
							                
							        <td>{{ $sla_report->user->userDetail->companyMaster->company_name ?? 'N/A' }}</td>
							        <td>{{ $sla_report->user->userDetail->locationMaster->working_location ?? 'N/A' }}</td>      
							      <td>{{ $sla_report->expense_id }}</td>
							      <td>{{ \Carbon\Carbon::parse($sla_report->expense_date)->format('F') }}</td>
							      <td>
							      		@if ($sla_report->status == 3)
									        <span class="text-danger">Rejected</span>
									    @elseif ($sla_report->status == 2)
									        <span class="text-warning">In Progress</span>
									    @elseif ($sla_report->status == 1)
									        <span class="text-success">Completed</span>
									    @elseif ($sla_report->status == 0)
									        <span class="text-info">Not Submitted</span>
									    @endif
							      </td>

								  
								  <td>{{ $sla_report->date_of_submission ? \Carbon\Carbon::parse($sla_report->date_of_submission)->format('d-m-Y') : 'N/A' }}</td>
								  
							    </tr>
						    @endforeach
						  </tbody>
						</table>