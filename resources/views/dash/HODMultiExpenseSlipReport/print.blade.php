<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-spacing: 8px;
            border-collapse: separate;
        }
        td {
            width: 33.33%;
            vertical-align: top;
        }
        .block {
            background-color: white;
            padding: 16px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        table.table-inner {
            width: 100%;
            border-collapse: collapse;
        }
        table.table-inner td {
            padding: 4px;
            border: 1px solid #ddd;
        }
        table.table-inner tr:nth-child(odd) {
            background-color: #f8f9fa;
        }
        .table-title {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        .total-cell {
            color: #0d6efd;
            text-align: center;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <table>
        <tr>
    @foreach ($results as $index => $result)
        <td>
            <div class="">
                <div style="width: 100%; padding: 8px; box-sizing: border-box;">
                    <div style="background-color: white; padding: 16px; border: 1px solid #ddd;">
                        <div style="display: grid; grid-template-columns: repeat(3,1fr);">
                            <p><span style="font-weight: 600;">Employee ID:</span> {{ $result['employee_id'] }}</p>
                            <p><span style="font-weight: 600;">Employee Name:</span> {{ $result['employee_name'] }}</p>
                            <p><span style="font-weight: 600;">Expense ID:</span> {{ $result['expense_id'] }}</p>
                        </div>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tbody>
                                <tr style="background-color: #f8f9fa; font-weight: bold;">
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
                                    <td colspan="3" style="color: #0d6efd; text-align: center; font-weight: bold;">Total</td>
                                    <td>
                                        <input type="text" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" value="{{ $result['total_da'] }}" disabled>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
                            <tbody>
                                <tr style="background-color: #f8f9fa; font-weight: bold;">

                                    <td></td>
                                    <td>1-15</td>
                                    <td>16-31</td>
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
                                    <td colspan="3" style="color: #0d6efd; text-align: center; font-weight: bold;">Total</td>
                                    <td>
                                        <input type="text" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" value="{{ $result['total'] }}" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="color: #0d6efd; text-align: center; font-weight: bold;">Grand Total</td>
                                    <td>
                                        <input type="text" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" value="{{ $result['grand_total'] }}" disabled>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </td>

        <!-- Check if this is the 3rd column, close the current row and start a new one -->
        @if (($index + 1) % 3 == 0)
            </tr><tr>
        @endif
    @endforeach
</tr>

    </table>
</body>
</html>
