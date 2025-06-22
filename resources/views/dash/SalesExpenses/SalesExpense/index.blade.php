@extends('layouts.main')
@section('title', 'Sales Expenses | Jmitra & Co. Pvt. Ltd')
@section('content')	
<!--*******************
            Main Content start
        *******************-->
        <div class="container-fluid px-4">
            <div class="row my-5">
                <div class="col">
                    <div class="d-grid d-flex justify-content-end">
                        <h5 class="text-info mt-2 me-3">Sales Expenses Analysis
                            @if(isset($monthName) && $monthName)
                                ( {{ \Carbon\Carbon::parse($monthName)->format('F Y') }} )
                            @endif
                            @if(isset($fromDate) && isset($toDate) && $fromDate && $toDate)
                                ( {{ \Carbon\Carbon::parse($fromDate)->format('j F, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('j F, Y') }})
                            @endif
                        </h5>
                        

                        <form action="{{ route('sale_expenses') }}" method="POST">
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
                        
                    </div>
                </div>
            </div>
            @if(isset($groupedByStateSales) && isset($groupedByStateExpenses))

            <div class="row my-5">
                <div class="col">
                    <div class="border table-header p-4 position-relative rounded-top-4">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-white">Expense Details Report</h5>
                                <form action="{{ route('sale_expenses_print') }}" method="POST">
                                @csrf
                                
                                        
                                <input type="month" name="monthName" id="monthName" class="form-control" value="{{ $print_data['monthName'] }}" hidden>
                                <input type="date" name="fromDate" id="fromDate" class="form-control" value="{{ $print_data['fromDate'] }}" hidden> 
                                <input type="date" name="toDate" id="toDate" class="form-control" value="{{ $print_data['toDate'] }}" hidden>                                                       
                                <button type="submit" class="btn btn-success text-light"><i class="fa-solid fa-download"></i></button>
                                
                                
                            </form>
                                
                            </div>
                    </div>
                <div class="col-12">
                    <div class="bg-white p-4">
                        <table class="table table-bordered table-hover table-light">
                            <tbody>
                                <tr class="table-active fw-bold">
                                    <th>State</th>
                                    <th>Expense</th>
                                    <th>Sales</th>
                                    <th>S/E Ratio</th>
                                </tr>
                                @foreach($combined_sales_expenses as $state => $data)
                                <tr>
                                    <td>{{ $state }}</td>
                                    <td>Rs. {{ number_format($data['expenses'] ?? 0, 2) }}</td>
                                    <td>Rs. {{ number_format($data['sales'] ?? 0, 2) }}</td>
                                    <td>{{ $data['sales_expense_ratio'] }}</td>
                                </tr>
                                @endforeach

                                

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
