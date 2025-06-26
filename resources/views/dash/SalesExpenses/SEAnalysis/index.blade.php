@extends('layouts.main')
@section('title', 'Sales And Expense Analysis | Jmitra & Co. Pvt. Ltd')
@section('content')
    <!--*******************
                            Main Content start
                        *******************-->
    <div class="container-fluid px-4">
        <div class="row my-5">
            <div class="col">
                <div class="d-grid d-flex justify-content-end">
                    <h5 class="text-info mt-2 me-3">S-E Analysis
                        @if (isset($monthName) && $monthName)
                            ( {{ \Carbon\Carbon::parse($monthName)->format('F Y') }} )
                        @endif
                        @if (isset($fromDate) && isset($toDate) && $fromDate && $toDate)
                            ( {{ \Carbon\Carbon::parse($fromDate)->format('j F, Y') }} -
                            {{ \Carbon\Carbon::parse($toDate)->format('j F, Y') }})
                        @endif
                    </h5>



                    <form action="{{ route('sale_se_analysis') }}" method="POST">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <label for="monthName" class="form-label">Select Month</label>
                                <!-- <input type="month" name="monthName" id="monthName" class="form-control" value="{{ old('monthName', $monthName ?? '') }}"> -->
                                <input type="month" name="monthName" id="monthName" class="form-control">
                            </div>

                            <div class="col-auto">
                                <label for="fromDate" class="form-label">From</label>
                                <!-- <input type="date" name="fromDate" id="fromDate" class="form-control" value="{{ old('fromDate', $fromDate ?? '') }}"> -->
                                <input type="date" name="fromDate" id="fromDate" class="form-control">
                            </div>
                            <div class="col-auto">
                                <label for="toDate" class="form-label">To</label>
                                <!-- <input type="date" name="toDate" id="toDate" class="form-control" value="{{ old('toDate', $toDate ?? '') }}"> -->
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

        @if (isset($totalExpenses) && isset($totalSales))
            <div class="row my-5">
                <div class="border table-header p-4 position-relative rounded-top-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="text-white">Sale Expense Analysis</h5>
                        <a href="{{ route('se_analysis_print') }}">
                            <button type="submit" class="btn btn-success text-light"><i
                                    class="fa-solid fa-download"></i></button>
                        </a>
                        {{-- <button id="downloadChartBtn" class="btn btn-success text-light">
                            <i class="fa-solid fa-download"></i>
                        </button> --}}

                    </div>
                </div>

                <div class="container text-center">
                    <canvas id="comparisonChart"></canvas>

                </div>

            </div>
        @endif

    </div>
    <!--*******************
                            Main Content End
                         *****************-->


@endsection
@section('additional_script')
    @if (isset($totalExpenses) && isset($totalSales))
        {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> --}}
        {{-- <script>
    const ctx = document.getElementById('comparisonChart').getContext('2d');
const comparisonChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($stateNames) !!} || [],
        datasets: [
            {
                label: 'Expenses',
                data: {!! json_encode($totalExpenses) !!} || [],
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Sales',
                data: {!! json_encode($totalSales) !!} || [],
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, position: 'top' },
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
                    return value.toLocaleString();
                },
                color: '#333',
                font: { weight: 'bold', size: 12 }
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
    // plugins: [ChartDataLabels],
    // Add a callback to save the chart after rendering is complete
    plugins: [ChartDataLabels,
        {
            id: 'saveImage',
            afterRender: function(chart) {
                setTimeout(() => {
                    const chartImage = chart.toBase64Image();
                    fetch('{{ route('save_se_analysis_chart_image') }}', {
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
</script> --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0/dist/chartjs-plugin-annotation.min.js">
        </script>




        <script>
            const ctx = document.getElementById('comparisonChart').getContext('2d');

            // Register plugins
            Chart.register(ChartDataLabels);
            Chart.register(window['chartjs-plugin-annotation']);

            const comparisonChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($stateNames) !!},
                    datasets: [{
                        label: 'Sales/Expense Ratio (%)',
                        data: {!! json_encode($salesExpenseRatio) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            color: '#000',
                            font: {
                                size: 10
                            },
                            formatter: function(value) {
                                return value.toFixed(2) + '%';
                            }
                        }
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        datalabels: {
                            display: true
                        },
                        annotation: {
                            annotations: {
                                avgLine: {
                                    type: 'line',
                                    yMin: {{ $averageRatio }},
                                    yMax: {{ $averageRatio }},
                                    borderColor: 'rgba(255, 206, 86, 1)',
                                    borderWidth: 2,
                                    borderDash: [6, 6],
                                    label: {
                                        content: 'Avg Ratio: {{ $averageRatio }}%',
                                        enabled: true,
                                        position: 'end',
                                        backgroundColor: 'rgba(255, 206, 86, 0.8)',
                                        color: '#000'
                                    },
                                    yScaleID: 'y'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Sales/Expense Ratio (%)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        </script>
    @endif
@endsection
