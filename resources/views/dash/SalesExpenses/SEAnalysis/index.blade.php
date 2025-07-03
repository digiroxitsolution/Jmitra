@extends('layouts.main')

@section('title', 'Sales And Expense Analysis | Jmitra & Co. Pvt. Ltd')

@section('content')
<div class="container-fluid px-4">
    <div class="row my-5">
        <div class="col">
            <div class="d-flex justify-content-between align-items-end flex-wrap">
                <h5 class="text-info">
                    S-E Analysis
                    @if (!empty($monthName))
                        ({{ \Carbon\Carbon::parse($monthName)->format('F Y') }})
                    @endif
                    @if (!empty($fromDate) && !empty($toDate))
                        ({{ \Carbon\Carbon::parse($fromDate)->format('j F, Y') }} -
                        {{ \Carbon\Carbon::parse($toDate)->format('j F, Y') }})
                    @endif
                </h5>

                <form action="{{ route('sale_se_analysis') }}" method="POST" class="row gx-2 align-items-end">
                    @csrf
                    <div class="col-auto">
                        <label for="monthName" class="form-label">Select Month</label>
                        <input type="month" name="monthName" id="monthName" class="form-control"
                            value="{{ old('monthName', $monthName ?? '') }}">
                    </div>
                    <div class="col-auto">
                        <label for="fromDate" class="form-label">From</label>
                        <input type="date" name="fromDate" id="fromDate" class="form-control"
                            value="{{ old('fromDate', $fromDate ?? '') }}">
                    </div>
                    <div class="col-auto">
                        <label for="toDate" class="form-label">To</label>
                        <input type="date" name="toDate" id="toDate" class="form-control"
                            value="{{ old('toDate', $toDate ?? '') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mt-4">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('includes.message')

    @if (isset($totalExpenses, $totalSales))
        <div class="row my-5">
            <div class="border table-header p-4 position-relative rounded-top-4 bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sales vs Expense Analysis</h5>
                    <a href="{{ route('se_analysis_print') }}" class="btn btn-success">
                        <i class="fa-solid fa-download"></i> Download
                    </a>
                </div>
            </div>

            {{-- Chart --}}
            <div class="container py-4 text-center bg-light rounded-bottom-4 shadow-sm">
                <canvas id="comparisonChart"></canvas>
            </div>

          
        </div>
    @endif
</div>
@endsection

@section('additional_script')
@if (isset($totalExpenses, $totalSales))
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0/dist/chartjs-plugin-annotation.min.js"></script>

<script>
    const ctx = document.getElementById('comparisonChart').getContext('2d');

    Chart.register(ChartDataLabels);
    Chart.register(window['chartjs-plugin-annotation']);

    const labels = {!! json_encode($stateLabels) !!};
    const data = {!! json_encode($salesExpenseRatio) !!};
    const averageRatio = {{ $averageRatio }};

    const maxRatio = Math.max(...data, averageRatio) * 1.2;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Expense/Sales Ratio',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    color: '#000',
                    font: { size: 10 },
                    formatter: val => val.toFixed(2)+'%'
                }
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' },
                datalabels: { display: true },
                annotation: {
                    annotations: {
                        avgLine: {
                            type: 'line',
                            yMin: averageRatio,
                            yMax: averageRatio,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            borderDash: [6, 6],
                            label: {
                                content: 'Overall S/E: ' + averageRatio.toFixed(2)+'%',
                                enabled: true,
                                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                                color: '#000',
                                font: { weight: 'bold' },
                                position: 'center',
                                yAdjust: -5
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Expense/Sales Ratio' },
                    ticks: {
                        callback: value => value.toFixed(2)
                    },
                    grace: '10%',
                    max: maxRatio
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>

@endif
@endsection
