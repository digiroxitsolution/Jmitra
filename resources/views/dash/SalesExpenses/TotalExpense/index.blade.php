@extends('layouts.main')
@section('title', 'Sale Total Expense | Jmitra & Co. Pvt. Ltd')
@section('content') 
<!--*******************
            Main Content start
        *******************-->
        <div class="container-fluid px-4">
            <div class="row my-5">
                <div class="col">
                    <div class="d-grid d-flex justify-content-end">
                        <h5 class="text-info mt-2 me-3">Sale Total Expense 
                            @if(isset($monthName) && $monthName)
                                ( {{ \Carbon\Carbon::parse($monthName)->format('F Y') }} )
                            @endif
                            @if(isset($fromDate) && isset($toDate) && $fromDate && $toDate)
                                ( {{ \Carbon\Carbon::parse($fromDate)->format('j F, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('j F, Y') }})
                            @endif
                        </h5>
                        
                        <form action="{{ route('sale_total_expense') }}" method="POST">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <label for="monthName" class="form-label">Select Month</label>
                                    <!-- <input type="month" name="monthName" id="monthName" class="form-control" value="{{ old('monthName', $monthName ?? '') }}"> -->
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
            @include('includes.message')
            @if(isset($ExpenseFareReports))
            <div class="row my-5">
                <div class="border table-header p-4 position-relative rounded-top-4">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-white">Total Expense</h5>
                                <form action="{{ route('total_expense_print') }}" method="POST">                  
                                    @csrf
                                <input type="text" id="totalFare" name="totalFare" value="{{ $totalFare }}" hidden>
                                <input type="text" id="fromDate" name="fromDate" value="{{ $fromDate }}" hidden>
                                <input type="text" id="toDate" name="toDate" value="{{ $toDate }}" hidden>
                                <button type="submit" class="btn btn-success text-light"><i class="fa-solid fa-download"></i></button>                   
                                </form>
                                
                            </div>
                    </div>
                
                <div class="container text-center">
                    <h3>{{ $title }}</h3>
                    <h5>{{ strtoupper(date('F - Y', strtotime(request()->input('fromDate') ?: now()))) }} ({{ request()->input('fromDate') }} to {{ request()->input('toDate') }})</h5>
                    <div class="mb-4">
                        <h4 class="badge bg-primary p-3">Total Expense: {{ number_format($totalFare, 2) }}</h4>
                    </div>
                    <canvas id="fareChart"></canvas>
                </div>
            </div>
            @endif
        </div>
        <!--*******************
            Main Content End
         *****************-->
        

@endsection
@section('additional_script')
@if(isset($ExpenseFareReports))

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    const ctx = document.getElementById('fareChart').getContext('2d');
    const fareChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($stateNames) !!},
            datasets: [{
                label: 'Fare',
                data: {!! json_encode($totalExpenses) !!},
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw.toLocaleString();
                        }
                    }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: function(value) {
                        return value.toLocaleString(); // Format values as a number
                    },
                    color: 'black', // Text color
                    font: {
                        size: 12,
                        weight: 'bold',
                    },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                }
            }
        },
        // plugins: [ChartDataLabels], // Register the datalabels plugin
        plugins: [ChartDataLabels,
        {
            id: 'saveImage',
            afterRender: function(chart) {
                setTimeout(() => {
                    const chartImage = chart.toBase64Image();
                    fetch('{{ route('save_total_expense_chart_image') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ image: chartImage })
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data.message);
                        })
                        .catch(err => console.error('Error saving chart:', err));
                }, 500); // Allow some delay for the chart to finish rendering
            }
        }
    ]
    });
</script>
@endif
@endsection