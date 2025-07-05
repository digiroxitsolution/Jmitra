{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table style="width: 100%; border-collapse: collapse;">
        <tbody>
            <tr style="background-color: #049DD9; font-weight: bold; font-size: 30px;">
                <th style="border: 1px solid #dee2e6; padding: 8px; color:#ffffff;"> <strong>State</strong></th>
                <th style="border: 1px solid #dee2e6; padding: 8px; color:#ffffff;"> <strong>Expense</strong></th>
                <th style="border: 1px solid #dee2e6; padding: 8px; color:#ffffff;"> <strong>Sales</strong></th>
                <th style="border: 1px solid #dee2e6; padding: 8px; color:#ffffff;"> <strong>S/E Ratio</strong></th>
            </tr>
            @foreach ($combined_sales_expenses as $state => $data)
            <tr style="background-color: #c6dae1; font-size: 16px;">
                <td style="border: 1px solid #ffffff; padding: 8px; color:#000000;"><b>{{ $state }}</b></td>
                <td style="border: 1px solid #ffffff; padding: 8px; color:#000000;"><b>Rs. {{ number_format($data['expenses'] ?? 0, 2) }}</b></td>
                <td style="border: 1px solid #ffffff; padding: 8px; color:#000000;"><b>Rs. {{ number_format($data['sales'] ?? 0, 2) }}</b></td>
                <td style="border: 1px solid #ffffff; padding: 8px; color:#000000;"><b>{{ $data['sales_expense_ratio'] }}</b></td>
            </tr>
            @endforeach
            
            <!-- <tr style="background-color: #aaa9a9; font-weight: bold;">
                <td style="border: 1px solid #dee2e6; padding: 8px;">Total</td>
                <td style="border: 1px solid #dee2e6; padding: 8px;">5,15,174</td>
                <td style="border: 1px solid #dee2e6; padding: 8px;">2.51,01,000</td>
                <td style="border: 1px solid #dee2e6; padding: 8px;">2.05%</td>
            </tr> -->
        </tbody>
    </table>
</body>
</html> --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sales vs Expenses Report</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
            font-size: 14px;
        }

        th {
            background-color: #049DD9;
            color: #fff;
            font-size: 16px;
        }

        .zone-header {
            background-color: #c6dae1;
            font-weight: bold;
        }

        .zone-total {
            background-color: #d1d1d1;
            font-weight: bold;
        }

        .grand-total {
            background-color: #a9a9a9;
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2 style="text-align: center;">Sales vs Expenses Report</h2>

    @if (!empty($monthName))
        <p style="text-align: center;">
            ({{ \Carbon\Carbon::parse($monthName)->format('F Y') }})
        </p>
    @elseif (!empty($fromDate) && !empty($toDate))
        <p style="text-align: center;">
            ({{ \Carbon\Carbon::parse($fromDate)->format('j F, Y') }} -
            {{ \Carbon\Carbon::parse($toDate)->format('j F, Y') }})
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>State</th>
                <th>Expense</th>
                <th>Sales</th>
                <th>S/E Ratio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedData as $zone => $zoneData)
                <tr class="zone-header">
                    <td colspan="4">{{ $zone }}</td>
                </tr>

                @foreach ($zoneData['states'] as $row)
                    <tr>
                        <td>{{ $row['state'] }}</td>
                        <td>Rs. {{ number_format($row['expense'], 2) }}</td>
                        <td>Rs. {{ number_format($row['sales'], 2) }}</td>
                        <td>{{ number_format($row['ratio'], 2) }}%</td>
                    </tr>
                @endforeach

                <tr class="zone-total">
                    <td>{{ $zone }} TOTAL</td>
                    <td>Rs. {{ number_format($zoneData['totals']['expense'], 2) }}</td>
                    <td>Rs. {{ number_format($zoneData['totals']['sales'], 2) }}</td>
                    <td>
                        {{-- @php
                            $e = $zoneData['totals']['expense'];
                            $s = $zoneData['totals']['sales'];
                            echo $e > 0 ? number_format($e / $s, 2) . '%' : ($s > 0 ? '0%' : '0%');
                        @endphp --}}
                        @php
                            $e = $zoneData['totals']['expense'] ?? 0;
                            $s = $zoneData['totals']['sales'] ?? 0;

                            if ($s > 0) {
                                $ratio = number_format(($e / $s) * 100, 2) . '%';
                            } elseif ($e > 0) {
                                $ratio = '0%';
                            } else {
                                $ratio = '0%';
                            }
                        @endphp
                    </td>
                </tr>
            @endforeach

            <tr class="grand-total">
                <td>GRAND TOTAL</td>
                <td>Rs. {{ number_format($grandExpenses ?? 0, 2) }}</td>
                <td>Rs. {{ number_format($grandSales ?? 0, 2) }}</td>
                <td>{{ $grandRatio ?? '0%' }}%</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
