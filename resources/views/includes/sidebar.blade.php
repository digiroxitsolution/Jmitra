<!--*******************
			Sidebar start
		*******************-->
		<div class="bg-white" id="sidebar-wrapper">
			<div class="sidebar-heading py-4 border-bottom mx-3 text-center fs-4 fw-bold text-uppercase primary-text border-success border-2">
				<img src="{{ asset('assets/images/Logo.png') }}" alt="Logo">
			</div>
			<h5 class="text-center mb-3 mt-4 role">
				@php
				    $user = Auth::user();
				    $roles = $user->getRoleNames();  // Retrieve roles
				@endphp
				
    
			    @if($roles->isNotEmpty())
			        
			            @foreach($roles as $role)
			                {{ $role }}
			            @endforeach
			        
			    @else
			        No roles
			    @endif

			</h5>
			<div class="list-group list-group-flush">
				<a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('dashboard') ? 'active' : '' }}">
					<i class="fa-solid fa-table-cells-large me-3"></i> Dashboard
				</a>
				@can('users-list')
				<a href="{{ route('users.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('users.index') ? 'active' : '' }}">
					<i class="fa-solid fa-user-tie me-3"></i> User Master
				</a>
				@endcan
				@can('roles-list')
				<a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('roles.index') ? 'active' : '' }}">
					<i class="fa fa-user-circle me-3"></i> Roles
				</a>
				@endcan
				@can('permissions-list')
				<a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('permissions.index') ? 'active' : '' }}">
					<i class="fa fa-lock me-3"></i> Permissions
				</a>
				@endcan
				@can('state-list')
				<a href="{{ route('state.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('state.index') ? 'active' : '' }}">
					<i class="fa-solid fa-map-marker me-3"></i>  State
				</a>
				@endcan
				@can('city-list')
				<a href="{{ route('city.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('city.index') ? 'active' : '' }}">
					<i class="fas fa-city me-3"></i>  City
				</a>
				@endcan

				@can('policy')
			    <!-- Policy dropdown -->
			    <a href="#" 
			        class="list-group-item border-bottom border-2 bg-transparent second-text 
			            {{ request()->routeIs('policy_settings.index') || request()->routeIs('policy_guidelines.index') ? 'active' : '' }}" 
			        data-bs-toggle="collapse" 
			        data-bs-target="#plicyDropdown" 
			        aria-expanded="{{ request()->routeIs('policy_settings.index') || request()->routeIs('policy_guidelines.index') ? 'true' : 'false' }}" 
			        aria-controls="policyDropdown">
			        <i class="fa-solid fa-chart-line me-3"></i> Policy
			        <button class="btn btn-link p-0 ms-3" type="button">
			            <i class="fa-solid fa-chevron-down text-white"></i>
			        </button>
			    </a>

			    <!-- Dropdown Menu -->
			    <div class="collapse {{ request()->routeIs('policy_settings.index') || request()->routeIs('policy_guidelines.index') ? 'show' : '' }}" id="plicyDropdown">
			        @can('policy-setting-list')
			            <a href="{{ route('policy_settings.index') }}" 
			                class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text 
			                    {{ request()->routeIs('policy_settings.index') ? 'active' : '' }}">
			                Policy Setting
			            </a>
			        @endcan

			        @can('policy-guidelines-list')
			            <a href="{{ route('policy_guidelines.index') }}" 
			                class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text 
			                    {{ request()->routeIs('policy_guidelines.index') ? 'active' : '' }}">
			                Policy Guidelines
			            </a>
			        @endcan
			    </div>
			@endcan


				@can('sales-list')
				<a href="{{ route('sales_master.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('sales_master.index') ? 'active' : '' }}">
					<i class="fa-solid fa-chart-line me-3"></i> Sales Master
				</a>
				@endcan

				@can('company-list')
				<a href="{{ route('company_master.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('company_master.index') ? 'active' : '' }}">
					<i class="fa-solid fa-building me-3"></i> Company Master
				</a>
				@endcan

				

				@can('designation-list')
				<a href="{{ route('designation.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('designation.index') ? 'active' : '' }}">
					<i class="fa-solid fa-user-tag me-3"></i> Designation Master
				</a>
				@endcan

				@can('hod-list')
				<a href="{{ route('hods.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('hods.index') ? 'active' : '' }}">
					<i class="fa-solid fa-chalkboard-user me-3"></i> HOD Master
				</a>
				@endcan

				@can('mode-of-expense-list')
				<a href="{{ route('mode_of_expense_master.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('mode_of_expense_master.index') ? 'active' : '' }}">
					<i class="fa-solid fa-money-bill-transfer me-3"></i> Mode of Expense Master
				</a>
				@endcan

				@can('other-expense-list')
				<a href="{{ route('other_expense_master.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('other_expense_master.index') ? 'active' : '' }}">
					<i class="fa-solid fa-circle-dollar-to-slot me-3"></i> Other Expense Master
				</a>
				@endcan

				@can('expense-type-list')
				<a href="{{ route('expense_type.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('expense_type.index') ? 'active' : '' }}">
					<i class="fa-solid fa-hand-holding-dollar me-3"></i> Expense Type Master
				</a>
				@endcan

				@can('way-of-location-list')
				<a href="{{ route('way_of_location_master.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('way_of_location_master.index') ? 'active' : '' }}">
					<i class="fa-solid fa-map-location-dot me-3"></i> Way of Location Master
				</a>
				@endcan

				@can('divison-list')
				<a href="{{ route('divison_master.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('divison_master.index') ? 'active' : '' }}">
					<i class="fa-solid fa-divide me-3"></i> Division Master
				</a>
				@endcan

				@can('location-list')
				<a href="{{ route('location_master.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('location_master.index') ? 'active' : '' }}">
					<i class="fa-solid fa-location-dot me-3"></i> Locations Master
				</a>
				@endcan

				@can('rejection-list')
				<a href="{{ route('rejection_master.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('rejection_master.index') ? 'active' : '' }}">
					<i class="fa-solid fa-rectangle-xmark me-3"></i> Rejected Master
				</a>
				@endcan

				@can('re-open-list')
				<a href="{{ route('re_open_master.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('re_open_master.index') ? 'active' : '' }}">
					<i class="fa-regular fa-folder-open me-3"></i> Re - Open Master
				</a>
				@endcan
				@can('monthly-expense')
				<a href="{{ route('monthly_expenses.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('monthly_expenses.index') ? 'active' : '' }}">
					<i class="fa-solid fa-credit-card me-3"></i> Monthly Expense
				</a>
				@endcan

				

				
				
				@can('expense-pending-for-approval')
				<a href="{{ route('pending_expense_verification.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('pending_expense_verification.index') ? 'active' : '' }}">
					<i class="fa-solid fa-spinner me-3"></i> Expense Pending For 
					@if (Auth::user()->hasRole('Sales Admin'))
					   Verification
					@elseif (Auth::user()->hasRole('Sales Admin Hod'))
					    Approval
					@endif					
				</a>
				@endcan

				@can('attendance-list')
				<a href="{{ route('attendance.index') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
					<i class="fa-solid fa-circle-check me-3"></i> Attendance
				</a>
				@endcan
				
				@can('multi-expense-slip-report')
				<a href="{{ route('expenses_slip_report') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('expenses_slip_report') ? 'active' : '' }}">
					<i class="fa-solid fa-receipt me-3"></i> Expense Slip Report
				<a href="{{ route('multi_expenses_slip_report') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('multi_expenses_slip_report') ? 'active' : '' }}">
					<i class="fa-solid fa-file-invoice-dollar me-3"></i> Multi Expense Slip Report
				</a>
				@endcan

				@can('expense-details-report')
				<a href="{{ route('expense_details_report') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('expense_details_report') ? 'active' : '' }}">
					<i class="fa-solid fa-circle-info me-3"></i> Expense Details Report
				</a>
				@endcan

				@can('process-report')
				<a href="{{ route('process_report') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('process_report') ? 'active' : '' }}">
					<i class="fa-solid fa-circle-info me-3"></i> Process Report
				</a>
				@endcan

				@can('genex-report')
				<a href="{{ route('genex_report') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('genex_report') ? 'active' : '' }}">
					<i class="fa-solid fa-circle-dollar-to-slot me-3"></i> Genex Report
				</a>
				@endcan

				@can('calc')
				<a href="{{ route('hod_calc') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('hod_calc') ? 'active' : '' }}">
					<i class="fa-solid fa-calculator me-3"></i> Calc
				</a>
				@endcan

				@can('sale-expenses')
			    <!-- Sales Expense dropdown -->
			    <a href="#" 
			        class="list-group-item border-bottom border-2 bg-transparent second-text 
			            {{ request()->routeIs('sale_expenses') || request()->routeIs('sale_se_analysis') || request()->routeIs('sale_total_expense') || request()->routeIs('sale_fare') || request()->routeIs('sale_pax') ? 'active' : '' }}" 
			        data-bs-toggle="collapse" 
			        data-bs-target="#salesExpenseDropdown" 
			        aria-expanded="{{ request()->routeIs('sale_expenses') || request()->routeIs('sale_se_analysis') || request()->routeIs('sale_total_expense') || request()->routeIs('sale_fare') || request()->routeIs('sale_pax') ? 'true' : 'false' }}" 
			        aria-controls="salesExpenseDropdown">
			        <i class="fa-solid fa-chart-line me-3"></i> Sales Expense
			        <button class="btn btn-link p-0 ms-3" type="button">
			            <i class="fa-solid fa-chevron-down text-white"></i>
			        </button>
			    </a>

			    <!-- Dropdown Menu -->
			    <div class="collapse {{ request()->routeIs('sale_expenses') || request()->routeIs('sale_se_analysis') || request()->routeIs('sale_total_expense') || request()->routeIs('sale_fare') || request()->routeIs('sale_pax') ? 'show' : '' }}" id="salesExpenseDropdown">
			        @can('sale-expense')
			            <a href="{{ route('sale_expenses') }}" 
			                class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text 
			                    {{ request()->routeIs('sale_expenses') ? 'active' : '' }}">
			                Sales Expense
			            </a>
			        @endcan

			        @can('se-analysis')
			            <a href="{{ route('sale_se_analysis') }}" 
			                class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text 
			                    {{ request()->routeIs('sale_se_analysis') ? 'active' : '' }}">
			                SE Analysis
			            </a>
			        @endcan

			        @can('sale-total-expense')
			            <a href="{{ route('sale_total_expense') }}" 
			                class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text 
			                    {{ request()->routeIs('sale_total_expense') ? 'active' : '' }}">
			                Total Expense
			            </a>
			        @endcan

			        @can('sale-fare')
			            <a href="{{ route('sale_fare') }}" 
			                class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text 
			                    {{ request()->routeIs('sale_fare') ? 'active' : '' }}">
			                Fare
			            </a>
			        @endcan

			        @can('sale-pax')
			            <a href="{{ route('sale_pax') }}" 
			                class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text 
			                    {{ request()->routeIs('sale_pax') ? 'active' : '' }}">
			                PAX
			            </a>
			        @endcan
			    </div>
			@endcan



				@can('general-expenses')
				<a href="{{ route('general_expenses') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('general_expenses') ? 'active' : '' }}">
					<i class="fa-brands fa-creative-commons-share me-3"></i> General Expense
				</a>
				@endcan
				@can('status-of-expense')
				<a href="{{ route('status_of_expenses') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('status_of_expenses') ? 'active' : '' }}">
					<i class="fa-solid fa-chart-simple me-3"></i> Status of Expenses
				</a>
				@endcan

				@can('process-time-report')
				<a href="{{ route('process_time_report') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('process_time_report') ? 'active' : '' }}">
					<i class="fa-regular fa-clock me-3"></i> Process Time Report
				</a>
				@endcan

				@can('sla-report')
				<a href="{{ route('sla_report') }}" class="list-group-item list-group-item-action border-bottom border-2 bg-transparent second-text {{ request()->routeIs('sla_report') ? 'active' : '' }}">
					<i class="fa-solid fa-square-poll-vertical me-3"></i> SLA Report
				</a>
				@endcan

				

			</div>
		</div>
		<!--*******************
			Sidebar end
		*******************-->