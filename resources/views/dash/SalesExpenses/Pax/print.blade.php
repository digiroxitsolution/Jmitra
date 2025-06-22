<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total PAX</title>
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
    <h1 style="text-align: center;">Total PAX</h1>
    <h5 style="text-align: center;">
        @if(isset($monthName) && $monthName)
                                ( {{ \Carbon\Carbon::parse($monthName)->format('F Y') }} )
                            @endif
                            @if(isset($fromDate) && isset($toDate) && $fromDate && $toDate)
                                ( {{ \Carbon\Carbon::parse($fromDate)->format('j F, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('j F, Y') }})
                            @endif
                        </h5>
    <h4 style="text-align: center;">Total PAX: {{ $totalFare }}</h4>
    <div class="chart-container">
        <img src="{{ $chartPath }}" alt="Total PAX Chart">
    </div>
</body>
</html>
