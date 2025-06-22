<!-- Display the table for 'Sales Admin' role -->
						    <table id="example" class="table bg-white rounded shadow-sm table-hover">
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
						                <th scope="col">Reason of Rejected</th>
						                <th scope="col">Action</th>
						            </tr>
						        </thead>
						        <tbody>
						            @php $i = 1 @endphp
						            @foreach ($monthly_expenses as $monthly_expense)
						            <tr>
						                <td>{{ $i++ }}</td>
						                <td>{{ $monthly_expense->user->userDetail->employee_id ?? 'N/A' }}</td>
						                <td>{{ $monthly_expense->user->name ?? 'N/A' }}</td>

						                <td>{{ $monthly_expense->user->userDetail->hod->name ?? 'N/A' }}</td>
						                
						                <td>{{ $monthly_expense->user->userDetail->companyMaster->company_name ?? 'N/A' }}</td>
						                <td>{{ $monthly_expense->user->userDetail->locationMaster->working_location ?? 'N/A' }}</td>
						                <td>{{ $monthly_expense->expense_id }}</td>
						                <td>{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('F') }}</td>
						                <td>
										    @php
												    $status = $monthly_expense->status;
												    $is_verified = $monthly_expense->is_verified;
												    $is_approved = $monthly_expense->is_approved;

												    $progress = 0;
												    $color = 'secondary';
												    $label = '';

												    if ($status == 3) {
												        // Rejected
												        if ($is_verified == 0 && $is_approved == 0) {
												            $progress = 75;
												            $color = 'danger';
												            $label = 'Rejected by Admin';
												        } elseif ($is_verified == 1 && $is_approved == 0) {
												            $progress = 100;
												            $color = 'danger';
												            $label = 'Rejected by HOD';
												        } else {
												            $progress = 0;
													        $color = 'danger';
													        $label = 'Rejected';
												        }
												        
												    } elseif ($status == 2) {
												        if ($is_verified == 1 && $is_approved == 1) {
												            $progress = 100;
												            $color = 'success';
												            $label = 'Completed';
												        } elseif ($is_verified == 1 && $is_approved == 0) {
												            $progress = 75;
												            $color = 'info';
												            $label = 'In Progress';
												        } else {
												            $progress = 50;
												            $color = 'warning';
												            $label = 'Pending';
												        }
												    } elseif ($status == 0) {
												        // Not Submitted
												        $progress = 25;
												        $color = 'info';
												        $label = 'Not Submitted';
												    } elseif ($status == 1) {
												        // Completed
												        if ($is_verified == 1 && $is_approved == 1) {
												            $progress = 100;
												            $color = 'success';
												            $label = 'Completed';
												        } elseif ($is_verified == 1 && $is_approved == 0) {
												            $progress = 75;
												            $color = 'info';
												            $label = 'In Progress';
												        } else {
												            $progress = 50;
												            $color = 'warning';
												            $label = 'In Progress';
												        }
												    }
												@endphp

												<div class="progress" style="height: 20px;">
												    <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
												        {{ $label }}
												    </div>
												</div>


										    <div class="mt-2">
										        @if ($monthly_expense->is_verified == 1)
										            <span class="badge bg-success me-1">Verified</span>
										        @else
										            <span class="badge bg-secondary me-1">Not Verified</span>
										        @endif

										        @if ($monthly_expense->is_approved == 1)
										            <span class="badge bg-primary">Approved</span>
										        @else
										            <span class="badge bg-secondary">Not Approved</span>
										        @endif
										    </div>
										</td>
						                <td>{{ \Illuminate\Support\Str::limit($monthly_expense->reason_of_rejected, 30) }}</td>
						                <td>
						                    <div class="d-flex">
						                        <span>
						                            <a href="{{ route('statement_of_expense', $monthly_expense->id) }}">
						                                <i class="fa-solid fa-eye bg-info text-white p-1 rounded-circle shadow me-2"></i>
						                            </a>
						                        </span>
						                    </div>
						                </td>
						            </tr>
						            @endforeach
						        </tbody>
						    </table>