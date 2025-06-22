<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
      <thead>
        <tr>
          <td colspan="8" style="padding: 4px; text-align: left; font-weight: 700;">From:  {{ $print_data['fromDate'] }}</td>
          <td colspan="8" style="padding: 4px; text-align: left; font-weight: 700;">To:  {{ $print_data['toDate'] }}</td>
        </tr>
        <tr>
          <th style="border: 1px solid #000; padding: 4px;">Month</th>
          <th style="border: 1px solid #000; padding: 4px;">Company</th>
          <th style="border: 1px solid #000; padding: 4px;">State</th>
          <th style="border: 1px solid #000; padding: 4px;">WL</th>
          <th style="border: 1px solid #000; padding: 4px;">Name</th>
          <th style="border: 1px solid #000; padding: 4px;">WL_Days_1_15</th>
          <th style="border: 1px solid #000; padding: 4px;">Out_Days_1_15</th>
          <th style="border: 1px solid #000; padding: 4px;">WL_Days_16_31</th>
          <th style="border: 1px solid #000; padding: 4px;">Out_Days_16_31</th>
          <th style="border: 1px solid #000; padding: 4px;">Fare_1_15</th>
          <th style="border: 1px solid #000; padding: 4px;">Fare_16_31</th>
          <th style="border: 1px solid #000; padding: 4px;">GENEX</th>
          <th style="border: 1px solid #000; padding: 4px;">GENEX Remarks</th>
          <th style="border: 1px solid #000; padding: 4px;">Postage</th>
          <th style="border: 1px solid #000; padding: 4px;">Mob_Internet</th>
          <th style="border: 1px solid #000; padding: 4px;">Print_Stat</th>
          <th style="border: 1px solid #000; padding: 4px;">Fare Total</th>
          <th style="border: 1px solid #000; padding: 4px;">DA Total</th>
          <th style="border: 1px solid #000; padding: 4px;">Others</th>
          <th style="border: 1px solid #000; padding: 4px;">Total Month</th>
          <th style="border: 1px solid #000; padding: 4px;">DA_WL</th>
          <th style="border: 1px solid #000; padding: 4px;">DA_Out</th>
        </tr>
      </thead>
      <tbody>
        @foreach($results as $key => $result)
        <tr>
          
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['monthName']}}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['company']}}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['state' ]}}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['location' ]}}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['employee_name' ]}}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['fifteen_days_of_monthly_expenses_headquarters_days'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['fifteen_days_of_monthly_expenses_outstation_days'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['rest_of_monthly_expenses_headquarters_days'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['rest_of_monthly_expenses_outstation_days'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['fifteen_days_of_fare_amount'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['rest_of_other_fare_amount'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['total_genex'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">
            @if (is_array($result['genex_remarks']) && !empty($result['genex_remarks']))
                                            {{ implode(', ', $result['genex_remarks']) }}
            @endif

          </td>

          <td style="border: 1px solid #000; padding: 4px;">{{ $result['total_postage'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['total_mobile_internet'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['total_print_stationery'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['total_fare'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['da_total'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['total_other_expenses_amount'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['total_month'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['total_da_working_location'] }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $result['total_working_da_outstation'] }}</td>
        </tr>
        @endforeach
        <tr>
          <td colspan="5" style="border: 1px solid #000; padding: 4px; text-align: center;">Total</td>
          
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_fifteen_days_of_monthly_expenses_headquarters_days }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_fifteen_days_of_monthly_expenses_outstation_days }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_rest_of_monthly_expenses_headquarters_days }}</td>
          <!-- <td colspan="5" style="border: 1px solid #000; padding: 4px; text-align: center;">Total</td> -->
          
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_rest_of_monthly_expenses_outstation_days }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_fifteen_days_of_fare_amount }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_rest_of_other_fare_amount }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_total_genex }}</td>
          <td style="border: 1px solid #000; padding: 4px;"></td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_total_postage }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_total_mobile_internet }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_total_print_stationery }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $total_fare_amount }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_da_total }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_total_other_expenses_amount }}</td>
          <td style="border: 1px solid #000; padding: 4px;">{{ $all_total_month }}</td>
          <td style="border: 1px solid #000; padding: 4px;"></td>
          <td style="border: 1px solid #000; padding: 4px;"></td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>
