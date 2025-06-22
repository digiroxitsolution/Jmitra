@extends('layouts.main')
@section('title', 'Multi Expense Slip Report| Jmitra & Co. Pvt. Ltd')
@section('content') 
<!--*******************
            Main Content start
        *******************-->
        <div class="container-fluid px-4">
            <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-white">Multi 
                                    Expense Slip Report</h5>
                                <form action="{{ route('multi_expenses_slip_report_print') }}" method="POST">
                                    @csrf
                                
                                        
                                
                                    <input type="text" name="employee_id" id="employee_id" class="form-control" value="{{ $print_data['employee_id'] }}" hidden>
                                
                                    <input type="text" name="employee_name" id="employee_name" class="form-control" value="{{ $print_data['employee_name'] }}" hidden>
                                
                                    <input type="month" name="month_of" id="month_of" class="form-control" value="{{ $print_data['month_of'] }}" hidden>
                                
                                    <input type="text" name="expense_id" id="expense_id" class="form-control" value="{{ $print_data['expense_id'] }}" hidden>
                                    
                                        <button type="submit" class="btn btn-warning text-white">Print</button>
                                
                                
                                </form>
                            </div>
                    </div>
                    @include('includes.message')
                    <div class="bg-white p-4">
                        <div class="row mb-4">
                            <div class="col-lg-4 col-12">
                                <h5 class="text-muted">Your Company Name</h5>
                            </div>
                        </div>
                        <form action="{{ route('multi_expenses_slip_report_search') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-lg-6 col-12">
                                    <label for="employee_id">Employee ID:</label>
                                    <input type="text" name="employee_id" id="employee_id" class="form-control" value="{{ $print_data['employee_id'] }}">
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="employee_name">Employee Name:</label>
                                    <input type="text" name="employee_name" id="employee_name" class="form-control" value="{{ $print_data['employee_name'] }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 col-12">
                                    <label for="month_of">Month Of:</label>
                                    <input type="month" name="month_of" id="month_of" class="form-control" value="{{ $print_data['month_of'] }}">
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="expense_id">Expense ID:</label>
                                    <input type="text" name="expense_id" id="expense_id" class="form-control" value="{{ $print_data['expense_id'] }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                        
                        <hr>
                        
                        <div class="row">
                           @foreach ($results as $result)

                            <div class="col-md-4 border mb-4">
                                <div class="bg-white p-4">
                                        <div class="row">
                                            <p class="col-4 fw-bold">Employee ID:</p>
                                            <p class="col-4 fw-bold">Employee Name:</p>
                                            <p class="col-4 fw-bold">Expense ID:</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-4">{{ $result['employee_id'] }}</p>
                                            <p class="col-4">{{ $result['employee_name'] }}</p>
                                            <p class="col-4">{{ $result['expense_id'] }}</p>
                                        </div>
                                    </div>
                                <div class="bg-white p-4">                                 
                                    <table class="table table-bordered table-hover table-light">
                                        <tbody>
                                            <tr class="table-active fw-bold">
                                                <td></td>
                                                <td>WL DAYS</td>
                                                <td>Out Days</td>
                                                <td>SUM (Rs.)</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 110px;">DA (1-15)</td>
                                                <td>{{ $result['fifteen_days_of_monthly_expenses_headquarters_days'] }} Days</td>
                                                <td>{{ $result['fifteen_days_of_monthly_expenses_outstation_days'] }} Days</td>
                                                <td>Rs. {{ $result['fifteen_days_of_monthly_expenses_da'] }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 110px;">DA (16-31)</td>
                                                <td>{{ $result['rest_of_monthly_expenses_headquarters_days'] }} Days</td>
                                                <td>{{ $result['rest_of_monthly_expenses_outstation_days'] }} Days</td>
                                                <td>Rs. {{ $result['rest_of_monthly_expenses_da'] }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-primary text-center fw-bold">TOTAL</td>
                                                <td><input type="text" class="form-control" value="{{ $result['total_da'] }}" disabled></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-hover table-light">
                                        <tbody>
                                            <tr class="table-active fw-bold">
                                                <td></td>
                                                <td>1-15</td>
                                                <td style="width: 110px;">16-31</td>
                                                <td>SUM (Rs.)</td>
                                            </tr>
                                            <tr>
                                                <td>Travel</td>
                                                <td>Rs. {{ $result['fifteen_days_of_fare_amount'] }}</td>
                                                <td style="width: 110px;">Rs. {{ $result['rest_of_other_fare_amount'] }}</td>
                                                <td>Rs. {{ $result['total_other_fare_amount'] }}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>GENEX (Other Expenses)</td>
                                                <td>Rs. {{ $result['fifteen_days_of_other_expenses_amount'] }}</td>
                                                <td style="width: 110px;">Rs. {{ $result['rest_of_other_expenses_amount'] }}</td>
                                                <td>Rs. {{ $result['total_other_expenses_amount'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>POSTAGE</td>
                                                <td>Rs. {{ $result['fifteen_days_of_monthly_expenses_postage'] }}</td>
                                                <td style="width: 110px;">Rs. {{ $result['rest_of_monthly_expenses_postage'] }}</td>
                                                <td>Rs. {{ $result['total_postage'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Mobile/ Internet</td>
                                                <td>Rs. {{ $result['fifteen_days_of_mobile_internet'] }}</td>
                                                <td style="width: 110px;">Rs. {{ $result['rest_of_monthly_mobile_internet'] }}</td>
                                                <td>Rs. {{ $result['total_mobile_internet'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Stationery</td>
                                                <td>Rs. {{ $result['fifteen_days_of_print_stationery'] }}</td>
                                                <td style="width: 110px;">Rs. {{ $result['rest_of_print_stationery'] }}</td>
                                                <td>Rs. {{ $result['total_print_stationery'] }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-primary text-center fw-bold">TOTAL</td>
                                                <td><input type="text" class="form-control" value="{{ $result['total'] }}" disabled></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-primary text-center fw-bold">GRAND TOTAL</td>
                                                <td><input type="text" class="form-control" value="{{ $result['grand_total'] }}" disabled></td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach

                        </div>                  
                     </div>
                </div>
            </div>
                
                
                
                
            </div>
        </div>
        <!--*******************
            Main Content End
         *****************-->
        
@endsection


