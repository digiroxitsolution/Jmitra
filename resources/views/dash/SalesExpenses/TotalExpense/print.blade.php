<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Expenses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .chart-container {
            text-align: center;
            margin-top: 20px;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Total Expenses</h1>
    <h5 style="text-align: center;">{{ strtoupper(date('F - Y', strtotime(request()->input('fromDate') ?: now()))) }} ({{ request()->input('fromDate') }} to {{ request()->input('toDate') }})</h5>
    <h4 style="text-align: center;">Total Expense: {{ $totalFare }}</h4>
    <div class="chart-container">
        <img src="{{ $chartPath }}" alt="Sales and Expenses Chart">
    </div>
</body>
</html>
