@extends('layouts.main')
@section('title', 'Dashboard | Jmitra & Co. Pvt. Ltd')
@section('content')
		
		<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
			<div class="row g-3 mt-5">
        @if(auth()->user()->hasAnyRole(['Sales Admin Hod']))
        <div class="col-md-4 mb-md-0 mb-3">
          <div class="p-3 bg-white shadow rounded box">
            <i class="fa-solid fa-user-group bg-warning shadow fs-5 rounded-3 dashboard-icon p-3"></i>
            <h5 class="text-end">Total Expense Pending for Approval</h5>
            <h3 class="text-end">{{ $pending_for_approval }}</h3>
            <hr>
            <p class="text-end"><a href="{{ route('pending_expense_verification.index') }}" class="text-decoration-none text-primary">More Info</a></p>
          </div>
        </div>
        @endif

        @if(auth()->user()->hasAnyRole(['Sales Admin']))
        <div class="col-md-4 mb-md-0 mb-3">
          <div class="p-3 bg-white shadow rounded box">
            <i class="fa-solid fa-user-group bg-warning shadow fs-5 rounded-3 dashboard-icon p-3"></i>
            <h5 class="text-end">Total Expense Pending for Verification</h5>
            <h3 class="text-end">{{ $pending_for_verification }}</h3>
            <hr>
            <p class="text-end"><a href="{{ route('pending_expense_verification.index') }}" class="text-decoration-none text-primary">More Info</a></p>
          </div>
        </div>
        @endif

				@can('users-list')
        
				<div class="col-md-4 mb-md-0 mb-3">
					<div class="p-3 bg-white shadow rounded box">
						<i class="fa-solid fa-user-group shadow fs-5 rounded-3 dashboard-icon p-3"></i>
						<h5 class="text-end">Total Employee</h5>
						<h3 class="text-end">{{ $totalUsers }}</h3>
						<hr>
						<p class="text-end"><a href="{{ route('users.index') }}" class="text-decoration-none text-primary">More Info</a></p>
					</div>
				</div>
				@endcan
				@can('status-of-expense')
				<div class="col-md-4 mb-md-0 mb-3">
					<div class="p-3 bg-white shadow rounded box">
						<i class="fa-solid fa-user-group shadow fs-5 rounded-3 bg-primary  dashboard-icon p-3"></i>
						<h5 class="text-end">Status of Expenses</h5>
						<div class="d-flex justify-content-end rejected-completed-progress">
							<div class="me-4">
								<p class="p-0 m-0">Rejected</p>
								<p class="p-0 m-0">Completed</p>
								<p class="p-0 m-0">In Progress</p>
							</div>
							<div>
								<p class="p-0 m-0">{{ $no_of_rejected_status_of_expense ?? '0' }}</p>
								<p class="p-0 m-0">{{ $no_of_completed_status_of_expense ?? '0' }}</p>
								<p class="p-0 m-0">{{ $no_of_in_progress_status_of_expense ?? '0' }}</p>
							</div>
						</div>
						<hr>
						<p class="text-end"><a href="{{ route('status_of_expenses') }}" class="text-decoration-none text-primary">More Info</a></p>
					</div>
				</div>
				@endcan
				@can('process-time-report')

				<div class="col-md-4 mb-md-0 mb-3">
					<div class="p-3 bg-white shadow rounded box">
						<i class="fa-solid fa-user-group shadow fs-5 rounded-3 dashboard-icon p-3"></i>
						<h5 class="text-end">Process Time Report</h5>
						<!-- <p class="p-0 m-0 text-end">Process Name</p>
						<p class="p-0 m-0 text-end">Process Duration</p> -->
						<hr>
						<p class="text-end"><a href="{{ route('process_time_report') }}" class="text-decoration-none text-primary">More Info</a></p>
					</div>
				</div>
				@endcan
        @if(auth()->user()->hasAnyRole(['Super Admin']))
        <div class="col-md-4 mb-md-0 mb-3">
          <div class="p-3 bg-white shadow rounded box">
            <i class="fa-solid fa-user-group bg-warning shadow fs-5 rounded-3 dashboard-icon p-3"></i>
            <h5 class="text-end">Total Expense Pending for Approval</h5>
            <h3 class="text-end">{{ $pending_for_approval }}</h3>
            <hr>
            <!-- <p class="text-end"><a href="{{ route('pending_expense_verification.index') }}" class="text-decoration-none text-primary">More Info</a></p> -->
          </div>
        </div>
        
        <div class="col-md-4 mb-md-0 mb-3">
          <div class="p-3 bg-white shadow rounded box">
            <i class="fa-solid fa-user-group bg-warning shadow fs-5 rounded-3 dashboard-icon p-3"></i>
            <h5 class="text-end">Total Expense Pending for Verification</h5>
            <h3 class="text-end">{{ $pending_for_verification }}</h3>
            <hr>
            <!-- <p class="text-end"><a href="{{ route('pending_expense_verification.index') }}" class="text-decoration-none text-primary">More Info</a></p> -->
          </div>
        </div>
        @endif
			</div>
			<br>
			<div class="container">
				<!-- <div class="col-md-12">
					<div class="p-3 bg-white shadow rounded"> -->
						<div class="row my-4">
							
							<div class="col-md-6">
					            
					            <div id="employeeSlaChart"></div>

					        </div>
                  
					        @if(auth()->user()->hasAnyRole(['Super Admin', 'Sales Admin Hod', 'Sales Admin']))
                    <div class="col-md-6">
                        <div id="adminSlaChart"></div>
                    </div>
                @endif
						</div>
					<!-- </div>
				</div> -->
			</div>
		</div>
		<!--*******************
			Main Content end
		*******************-->
	
@endsection
@section('additional_script')


<script type="text/javascript">
window.onload = function () {
  // Employee SLA Chart
  var employee_chart = new CanvasJS.Chart("employeeSlaChart", {
    title: {
      text: "SLA Compliance by Employee",
      fontSize: 20,
      fontColor: "#333",
      backgroundColor: "#F0F0F0"
    },
    data: [
      {
        type: "doughnut",
        startAngle: 90,
        innerRadius: "50%",
        dataPoints: [
          { 
            y: {{ $sla_voilated }}, 
            indexLabel: "SLA Violated - #percent%",  // Show label and percentage
            color: "#FF4D4F", 
            borderColor: "#FFF", 
            borderThickness: 2,
            explosion: 0.2, // Make this slice explode slightly to make it appear thinner
            dataPointRadius: 15 // Adjust the radius to make it thinner
          },
          { 
            y: {{ $sla_non_voilated }}, 
            indexLabel: "SLA Non Violated - #percent%",  // Show label and percentage
            color: "#007BFF", 
            borderColor: "#FFF", 
            borderThickness: 2,
            explosion: 0, // No explosion effect for this slice
            dataPointRadius: 20 // Make this slice normal size
          }
        ]
      }
    ],
    toolTip: {
      enabled: true,
      backgroundColor: "#FFF",
      borderColor: "#007BFF",
      borderThickness: 2,
      fontSize: 12,
      fontColor: "#000000",
      content: "{label}: {y} (#percent%)"  // Display label and percentage in the tooltip
    },
    legend: {
      verticalAlign: "top",
      horizontalAlign: "center",
      fontSize: 14,
      fontFamily: "Arial",
      fontColor: "#000000"
    },
    animationEnabled: true,
    animationDuration: 2000,
    animationEasing: "easeOutBounce"
  });

  employee_chart.render();

  // Admin SLA Chart
  var adminChart = new CanvasJS.Chart("adminSlaChart", {
    title: {
      text: "SLA Compliance by Sales & Admin",
      fontSize: 20,
      fontColor: "#333",
      backgroundColor: "#F0F0F0"
    },
    data: [
      {
        type: "doughnut",
        startAngle: 90,
        innerRadius: "50%",
        dataPoints: [
          { 
            y: {{ $admin_hod_sla_voilated }},
            indexLabel: "SLA Violated - #percent%",  // Show label and percentage
            color: "#FF4D4F", 
            borderColor: "#FFF", 
            borderThickness: 2,
            explosion: 0.2, // Make this slice explode slightly to make it appear thinner
            dataPointRadius: 15 // Adjust the radius to make it thinner
          },
          { 
            y: {{ $admin_hod_sla_non_voilated }},
            indexLabel: "SLA Non Violated - #percent%",  // Show label and percentage
            color: "#007BFF", 
            borderColor: "#FFF", 
            borderThickness: 2,
            explosion: 0, // No explosion effect for this slice
            dataPointRadius: 20 // Make this slice normal size

          }
        ]
      }
    ],
    toolTip: {
      enabled: true,
      backgroundColor: "#FFF",
      borderColor: "#007BFF",
      borderThickness: 2,
      fontSize: 12,
      fontColor: "#000000",
      content: "{label}: {y} (#percent%)"  // Display label and percentage in the tooltip
    },
    legend: {
      verticalAlign: "top",
      horizontalAlign: "center",
      fontSize: 14,
      fontFamily: "Arial",
      fontColor: "#000000"
    },
    animationEnabled: true,
    animationDuration: 2000,
    animationEasing: "easeOutBounce"
  });

  adminChart.render();
}


 </script>
  <script src="{{ asset('assets/js/canvasjs.min.js') }}"></script>

@endsection