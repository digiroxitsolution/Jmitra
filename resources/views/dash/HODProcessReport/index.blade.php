@extends('layouts.main')
@section('title', 'Process Report | Jmitra & Co. Pvt. Ltd')
@section('content')	
<!--*******************
            Main Content start
        *******************-->
        <div class="container-fluid px-4">
            <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                        <h5 class="text-white">Process Report</h5>
                    </div>
                    <div class="bg-white p-4">
                        <form action="{{ route('process_report') }}" method="POST">
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
                        <br>
                        @if(isset($ExpenseDetailsReports))

                        <table class="table table-bordered table-hover table-light">
                            <tbody>
                                <tr class="table-active fw-bold">
                                    <th>Month</th>
                                    <th>Company</th>
                                    <th>State</th>
                                    <th>Calc</th>
                                    <th>Fare</th>
                                    <th>Total Month</th>
                                </tr>
                                @foreach($results as $key => $result)
                                <tr>
                                    <td>{{ $result['monthName']}}</td>
                                    <td>{{ $result['company']}}</td>
                                    <td>{{ $result['state' ]}}</td>                             
                                    <td>{{ $result['calc' ]}}</td>
                                    <td>{{ $result['one_month_of_total_fare_amount'] }}</td>
                                    <td>{{ $result['one_month_total' ] }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3">Total</td>
                                    <td>{{ $all_calc }}</td>
                                    <td>{{ $total_fare_amount }}</td>
                                    <td>{{ $all_one_month_total }}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
                <div class="row my-5">
                    <div class="col">
                        <div class="border table-header p-4 position-relative rounded-top-4">
                            <h5 class="text-white">Fare Report</h5>
                        </div>
                        <div class="bg-white p-4">
                            <table class="table table-bordered table-hover table-light">
                                <tbody>
                                    <tr class="table-active fw-bold">
                                        <th>State</th>
                                        <th>Total Fare Amount</th>
                                    </tr>
                                    @foreach($stateWiseDatas as $key => $stateWiseData)
                                    <tr>
                                        <td>{{ $stateWiseData['stateName']}}</td>
                                        <td>{{ $stateWiseData['total_fare'] }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ $total_fare_amount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                        <h5 class="text-white">Total Expenses</h5>
                    </div>
                    <div class="bg-white p-4">
                        <table class="table table-bordered table-hover table-light">
                            <tbody>
                                <tbody>
                                    <tr class="table-active fw-bold">
                                        <th>State</th>
                                        <th>Total Expenses Amount</th>
                                    </tr>
                                     @foreach($stateWiseDatas as $key => $stateWiseData)
                                    <tr>
                                        <td>{{ $stateWiseData['stateName']}}</td>
                                        <td>{{ $stateWiseData['total_expense']}}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ $all_total_expense }}</td>
                                    </tr>
                                </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                        <h5 class="text-white">Total PAX</h5>
                    </div>
                    <div class="bg-white p-4">
                        <table class="table table-bordered table-hover table-light">
                            <tbody>
                                <tbody>
                                    <tr class="table-active fw-bold">
                                        <th>State</th>
                                        <th>No. Of Pax</th>
                                    </tr>
                                    @foreach($stateWiseDatas as $key => $stateWiseData)
                                    <tr>
                                        <td>{{ $stateWiseData['stateName']}}</td>
                                        <td>{{ $stateWiseData['no_of_pax'] }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ $all_no_of_pax }}</td>
                                    </tr>
                                </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!--*******************
            Main Content End
         *****************-->
		

@endsection
