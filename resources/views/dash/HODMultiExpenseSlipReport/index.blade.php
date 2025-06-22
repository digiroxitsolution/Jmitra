@extends('layouts.main')
@section('title', 'Multi 
                                    Expense Slip Report| Jmitra & Co. Pvt. Ltd')
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
                                <!-- <button type="button" class="btn btn-warning text-white">Print</button> -->
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
                                    <input type="text" name="employee_id" id="employee_id" class="form-control" value="{{ old('employee_id') }}">
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="employee_name">Employee Name:</label>
                                    <input type="text" name="employee_name" id="employee_name" class="form-control" value="{{ old('employee_name') }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 col-12">
                                    <label for="month_of">Month Of:</label>
                                    <input type="month" name="month_of" id="month_of" class="form-control" value="{{ old('month_of') }}">
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="expense_id">Expense ID:</label>
                                    <input type="text" name="expense_id" id="expense_id" class="form-control" value="{{ old('expense_id') }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                        <hr>
                        

                       
                    </div>
                </div>
            </div>
                
                
                
                
            </div>
        </div>
        <!--*******************
            Main Content End
         *****************-->
		
@endsection
