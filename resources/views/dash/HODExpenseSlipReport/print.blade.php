<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div style="width: 100%; font-family: 'Merriweather', serif;">
        <div>
            <div style="background-color: white; padding: 16px; border: 1px solid #a8acb1;">
                <table style="width: 100%; line-height: 1.6; font-size: 14px; font-family: Arial, sans-serif;">
				    <tr>
				        <td style="width: 50%; padding-right: 16px;">
				            <p style="margin: 8px 0;">Expense ID: {{ $expense_id }}</p>
				            <p style="margin: 8px 0;">Expense Month: {{ $month }}</p>
				            <p style="margin: 8px 0;">Employee Name: {{ $user->name }}</p>
				            <p style="margin: 8px 0;">Employee ID: {{ $user->userDetail->employee_id }}</p>
				            <p style="margin: 8px 0;">Employee Designation: {{ $user->userDetail->designation->name }}</p>
				        </td>
				        <td style="width: 50%; padding-left: 16px;">
				            <p style="margin: 8px 0;">Location: {{ $user->userDetail->locationMaster->working_location }}</p>
				            <p style="margin: 8px 0;">Company Name: {{ $user->userDetail->companyMaster->company_name }}</p>
				            <p style="margin: 8px 0;">State: {{ $user->userDetail->state->name }}</p>
				            <p style="margin: 8px 0;">Working Location: {{ $user->userDetail->locationMaster->working_location }}</p>
				            <p style="margin: 8px 0;">Division: {{ $user->userDetail->DivisonMaster->name }}</p>
				        </td>
				    </tr>
				</table>



                <hr style="margin: 16px 0;">
                <table style="width: 100%; border-collapse: collapse; text-align: center; background-color: #f8f9fa; margin-bottom: 16px; border: 2px solid #a8acb1;">
                    <tbody>
                        <tr style="background-color: #e9ecef; font-weight: bold;">
                            <td style="border: 1px solid #dee2e6; padding: 8px;"></td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">HQ Days</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Out Days</td>
                            <td colspan="2" style="border: 1px solid #dee2e6; padding: 8px;">SUM (Rs.)</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">DA (1-15)</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $fifteen_days_of_monthly_expenses_headquarters_days }} Days</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $fifteen_days_of_monthly_expenses_outstation_days }} Days</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $fifteen_days_of_monthly_expenses_da }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">DA (16-31)</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $rest_of_monthly_expenses_headquarters_days }} Days</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $rest_of_monthly_expenses_outstation_days }} Days</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $rest_of_monthly_expenses_da }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border: 1px solid #dee2e6; padding: 8px;">TOTAL</td>
                            <td colspan="2" style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" style="width: 90%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" disabled value="{{ $total_da }}"></td>
                        </tr>
                    </tbody>
                </table>
                <table style="width: 100%; border-collapse: collapse; text-align: center; background-color: #f8f9fa; margin-bottom: 16px; border: 2px solid #a8acb1;">
                    <tbody>
                        <tr style="background-color: #e9ecef; font-weight: bold;">
                            <td style="border: 1px solid #dee2e6; padding: 8px;"></td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">1 - 15</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">16 - 31</td>
                            <td colspan="2" style="border: 1px solid #dee2e6; padding: 8px;">SUM (Rs.)</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Travel</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $fifteen_days_of_fare_amount }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $rest_of_other_fare_amount }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $total_other_fare_amount }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">GENEX (Other Expenses)</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $fifteen_days_of_other_expenses_amount }}</td>
							<td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $rest_of_other_expenses_amount }}</td>
							<td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $total_other_expenses_amount }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">POSTAGE</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $fifteen_days_of_monthly_expenses_postage }}</td>
							<td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $rest_of_monthly_expenses_postage }}</td>
							<td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $total_postage }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Mobile/ Internet</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $fifteen_days_of_mobile_internet }}</td>
							<td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $rest_of_monthly_mobile_internet }}</td>
							<td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $total_mobile_internet }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Stationery</td>
                            <td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $fifteen_days_of_print_stationery }}</td>
							<td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $rest_of_print_stationery }}</td>
							<td style="border: 1px solid #dee2e6; padding: 8px;">Rs. {{ $total_print_stationery }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border: 1px solid #dee2e6; padding: 8px;">Total</td>
                            <td colspan="2" style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" style="width: 90%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"  disabled value="{{ $total }}"></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border: 1px solid #dee2e6; padding: 8px;">Grand Total</td>
                            <td colspan="2" style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" style="width: 90%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" value="{{ $grand_total }}" class="form-control" disabled></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
</html>