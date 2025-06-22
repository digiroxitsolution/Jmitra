<!DOCTYPE html>
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
            @foreach($combined_sales_expenses as $state => $data)
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
</html>