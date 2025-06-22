@extends('layouts.main')
@section('title', 'Expense Details Report| Jmitra & Co. Pvt. Ltd')
@section('content')	
@can('expense-details-report') 
<!--******************* 
            Main Content start
        *******************-->
        <div class="container-fluid px-4">
            <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-white">Expense Details Report</h5>
                                @if(isset($ExpenseDetailsReports))
                                <form action="{{ route('expense_details_report_print') }}" method="POST">
                                @csrf
                                
                                        
                                <input type="month" name="monthName" id="monthName" class="form-control" value="{{ $print_data['monthName'] }}" hidden>
                                <input type="date" name="fromDate" id="fromDate" class="form-control" value="{{ $print_data['fromDate'] }}" hidden> 
                                <input type="date" name="toDate" id="toDate" class="form-control" value="{{ $print_data['toDate'] }}" hidden>                                                       
                                <button type="submit" class="btn btn-success text-light"><i class="fa-solid fa-download"></i></button>
                                
                                
                            </form>
                            @endif
                                
                            </div>
                    </div>
                    <div class="col">
                        <div class="p-4 position-relative rounded-top-4">

                            <form action="{{ route('expense_details_report') }}" method="POST">
                                    @csrf
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="monthName" class="form-label">Select Month</label>                                    
                                            <input type="month" name="monthName" id="monthName" class="form-control">
                                        </div>
                                        <div class="col-auto">
                                            <label for="fromDate" class="form-label">From</label>
                                            <input type="date" name="fromDate" id="fromDate" class="form-control">
                                        </div>
                                        <div class="col-auto">
                                            <label for="toDate" class="form-label">To</label>
                                            <input type="date" name="toDate" id="toDate" class="form-control">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary mt-4">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>
                    @if(isset($ExpenseDetailsReports))
                    <div class="table-responsive">                        
                        
                        <table class="table table-bordered table-hover table-light">
                          <thead>                           
                            
                            <tr class="table-active fw-bold">
                              <th scope="col">Month</i></th>
                              <th scope="col">Company</i></th>
                              <th scope="col">State</i></th>
                              <th scope="col">W L</i></th>
                              <th scope="col">Name</i></th>
                              <th scope="col">W L Days(1-15)</i></th>
                              <th scope="col">Out Days(1-15)</i></th>
                              <th scope="col">W L Days(16-31)</i></th>
                              <th scope="col">Out Days(16-31)</i></th>
                              <th scope="col">Fare(1-15)</i></th>
                              <th scope="col">Fare(16-31)</i></th>
                              <th scope="col">GENEX</i></th>
                              <th scope="col">GENEX Remarks</i></th>
                              <th scope="col">Postage</i></th>
                              <th scope="col">Mob Internet</i></th>
                              <th scope="col">Print Stationery</i></th>
                              <th scope="col">Fare Total</i></th>
                              <th scope="col">DA Total</i></th>
                              <th scope="col">Others</i></th>
                              <th scope="col">Total Month</i></th>
                              <th scope="col">DA W L</i></th>
                              <th scope="col">DA Out</i></th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($results as $key => $result)
                            <tr>
                            <td>{{ $result['monthName']}}</td>
                             <td>{{ $result['company']}}</td>
                             <td>{{ $result['state' ]}}</td>
                             <td>{{ $result['location' ]}}</td>
                             <td>{{ $result['employee_name' ]}}</td>
                             <td>{{ $result['fifteen_days_of_monthly_expenses_headquarters_days'] }}</td>
                             <td>{{ $result['fifteen_days_of_monthly_expenses_outstation_days'] }}</td>
                             <td>{{ $result['rest_of_monthly_expenses_headquarters_days'] }}</td>
                             <td>{{ $result['rest_of_monthly_expenses_outstation_days'] }}</td>
                             <td>{{ $result['fifteen_days_of_fare_amount'] }}</td>
                             <td>{{ $result['rest_of_other_fare_amount'] }}</td>
                             <td>{{ $result['total_genex'] }}</td>
                             <td>
                             @if (is_array($result['genex_remarks']) && !empty($result['genex_remarks']))
                                            {{ implode(', ', $result['genex_remarks']) }}
                             @endif

                            </td>

                             <td>{{ $result['total_postage'] }}</td>
                             <td>{{ $result['total_mobile_internet'] }}</td>
                             <td>{{ $result['total_print_stationery'] }}</td>
                             <td>{{ $result['total_fare'] }}</td>
                             <td>{{ $result['da_total'] }}</td>
                             <td>{{ $result['total_other_expenses_amount'] }}</td>
                             <td>{{ $result['total_month'] }}</td>
                             <td>{{ $result['total_da_working_location'] }}</td>
                             <td>{{ $result['total_working_da_outstation'] }}</td>
                            </tr>
                            @endforeach
                            
                            <tr class="table-active">
                                <td colspan="5">Total</td>
                                <td>{{ $all_fifteen_days_of_monthly_expenses_headquarters_days }}</td>
                                <td>{{ $all_fifteen_days_of_monthly_expenses_outstation_days }}</td>
                                <td>{{ $all_rest_of_monthly_expenses_headquarters_days }}</td>

                                <td>{{ $all_rest_of_monthly_expenses_outstation_days }}</td>
                                <td>{{ $all_fifteen_days_of_fare_amount }}</td>
                                <td>{{ $all_rest_of_other_fare_amount }}</td>
                                <td>{{ $all_total_genex }}</td>
                                <td></td>
                                <td>{{ $all_total_postage }}</td>
                                <td>{{ $all_total_mobile_internet }}</td>
                                <td>{{ $all_total_print_stationery }}</td>
                                <td>{{ $total_fare_amount }}</td>
                                <td>{{ $all_da_total }}</td>
                                <td>{{ $all_total_other_expenses_amount }}</td>
                                <td>{{ $all_total_month }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                          </tbody>
                        </table>
                        
                    </div>
                    @endif
                </div>
                
                
               
            </div>
        </div>
        <!--*******************
            Main Content End
         *****************-->
		

@else

@include('forbidden.forbidden')

@endcan
@endsection
