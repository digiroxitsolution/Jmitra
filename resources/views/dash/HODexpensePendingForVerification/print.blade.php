<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Expense</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- <button onclick="document.getElementById('printModal').style.display='block'" 
    style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
Open Modal -->
</button>
    <div  id="printModal" style="font-family: 'Merriweather', serif; border: 1px solid #a8acb1;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; background-color: #0594CB; color: #FFFFFF; position: sticky; top: 0%;">
                <h2 style="margin-left: 10px; text-align:center;">Monthly Expense Report</h2>
                <a href="#" style="padding: 8px 16px; background-color: #157347; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;"><i class="fa-solid fa-download"></i></a>
            </div>
            <div>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px; min-width: 200px;">Company Name: {{ $user->userDetail->companyMaster->company_name }}</td>
                        <td style="padding: 10px; min-width: 200px;">Division: {{ $user->userDetail->DivisonMaster->name }}</td>
                        <td style="padding: 10px; min-width: 200px;">Month: {{ \Carbon\Carbon::parse($pending_monthly_expenses->expense_date)->format('F') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; min-width: 200px;">Name: {{ $user->name }}</td>
                        <td style="padding: 10px; min-width: 200px;">Designation: {{ $user->userDetail->designation->name }}</td>
                        <td style="padding: 10px; min-width: 200px;">Location: {{ $user->userDetail->locationMaster->working_location }}</td>
                    </tr>
                </table>

                        
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: center;">
                            <tbody>
                                <tr style="background-color: #E5E6E7">
                                    <td style="border: 1px solid #a8acb1; padding: 0px 0px 0px 0px; width:90px;" rowspan="2">Date</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" rowspan="2">From</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" rowspan="2">To</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" colspan="2">Time</td>
                                    <td style="border: 1px solid #a8acb1; padding: 0px 0px 0px 0px" rowspan="">KM as per User</td>
                                    <td style="border: 1px solid #a8acb1; padding: 0px 0px 0px 0px;" rowspan="2">KM as per Google</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" rowspan="2">Mode</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" rowspan="2">Fare Amount</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" colspan="2">Daily Allowances</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" rowspan="2">Postage</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" rowspan="2">TEL/TGM</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" >Print Stationary</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" colspan="2">Other Expenses</td>
                                    
                                </tr>

                                <tr style="background-color: #E5E6E7">
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">DEP</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">ARR</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px"></td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">Working</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">N. Working</td>
                                    
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px"></td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">Purpose</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">Amount</td>
                                    
                                </tr>
                                @foreach($monthly_expenses as $key => $monthly_expense)
                                <tr>
                                    <td style="border: 1px solid #a8acb1; padding: 0px 0px 0px 0px"> 
                                        {{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('d-m-Y')  }}
                                    </td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">
                                        @if($monthly_expense->expense_type_master_id == 4 || $monthly_expense->expense_type_master_id == 5 || $monthly_expense->expense_type_master_id == 6 || $monthly_expense->expense_type_master_id == 9)

                                            {{ $monthly_expense->ExpenseTypeMaster->expense_type  }}
                                        @else
                                            {{ $monthly_expense->from }}
                                        @endif

                                        
                                    </td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">
                                        @if($monthly_expense->expense_type_master_id == 4 || $monthly_expense->expense_type_master_id == 5 || $monthly_expense->expense_type_master_id == 6 || $monthly_expense->expense_type_master_id == 9)

                                            {{ $monthly_expense->ExpenseTypeMaster->expense_type  }}
                                        @else
                                            {{ $monthly_expense->to }}
                                        @endif


                                    </td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">
                                        {{ $monthly_expense->departure_time ? \Carbon\Carbon::parse($monthly_expense->departure_time)->format('d-m-Y') : '' }}
                                        <hr>
                                        {{ $monthly_expense->departure_time ? \Carbon\Carbon::parse($monthly_expense->departure_time)->format('h:i A') : '' }}

                                        
                                    </td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">
                                        {{ $monthly_expense->arrival_time ? \Carbon\Carbon::parse($monthly_expense->arrival_time)->format('d-m-Y') : '' }}
                                        <hr>
                                        {{ $monthly_expense->arrival_time ? \Carbon\Carbon::parse($monthly_expense->arrival_time)->format('h:i A') : '' }}                                       
                                    </td>                                    

                                    <td style="border: 1px solid #a8acb1; padding: 0px 0px 0px 0px">{{ $monthly_expense->km_as_per_user }}</td>

                                    <td style="border: 1px solid #a8acb1; padding: 0px 0px 0px 0px">{{ $monthly_expense->km_as_per_google_map }}</td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $monthly_expense->ModeofExpenseMaster->mode_expense ?? '' }}</td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $monthly_expense->fare_amount }}</td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">@if ($monthly_expense->expense_type_master_id != 8)
                                                            {{ $monthly_expense->da_total }}
                                                        @endif</td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">@if ($monthly_expense->expense_type_master_id == 8)
                                                            {{ $monthly_expense->da_total }}
                                                        @endif</td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $monthly_expense->postage }}</td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $monthly_expense->mobile_internet }}</td>
                                  
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $monthly_expense->print_stationery }}</td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $monthly_expense->OtherExpenseMaster->other_expense ?? '' }}</td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $monthly_expense->other_expenses_amount }}</td>


                                    
                                </tr>
                                @endforeach
                                
                                <tr>
                                    
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px"  colspan="8">Total</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $total_fare_amount }}</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $total_da_location_working }}</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $total_da_location_not_working }}</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $total_postage }}</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $total_mobile_internet }}</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $total_other_expense_purpose }}</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $total_other_expense_amount }}</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">{{ $total_print_stationery }}</td>
                                    
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" colspan="7"></td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">RUPEES in Words</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" colspan="4">{{ $grand_total_in_words }}</td>

                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px">Grand Total</td>
                                    <td style="border: 1px solid #a8acb1; padding: 5px 10px 10px 15px" colspan="3">{{ $grand_total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px; text-align: left;">Signature of Field Staff: __________ __________ __________</td>
                        <td style="padding: 10px; text-align: left;">Signature of Manager: __________ __________ __________</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 10px;">
                            <input class="form-check-input me-2 ms-1" type="checkbox" value="1" id="accept_policy" name="accept_policy" {{ $pending_monthly_expenses->accept_policy == 1 ? 'checked' : '' }} {{ $pending_monthly_expenses->is_submitted === 1 ? 'disabled' : '' }} >
                            <label for="flexCheckIndeterminateAgree">
                                I hereby confirm that I verified the Expenses and found OK as per Travel/ Daily Allowance Policy.
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; min-width: 200px;">Submitted by<br>
                            {{ $pending_monthly_expenses->user?->name ?? 'N/A' }}<br>

                                    {{ $pending_monthly_expenses->expense_date ? \Carbon\Carbon::parse($pending_monthly_expenses->expense_date)->format('d-m-Y') : 'N/A' }}
                                    <br>
                                    {{ $pending_monthly_expenses->expense_date ? \Carbon\Carbon::parse($pending_monthly_expenses->expense_date)->format('h:i A') : 'N/A' }}
                        </td>

                        <td style="padding: 10px; min-width: 200px;">Verified by<br>
                            {{ $pending_monthly_expenses->verifiedBy?->name ?? 'N/A' }}<br>

                                    {{ $pending_monthly_expenses->verified_time ? \Carbon\Carbon::parse($pending_monthly_expenses->verified_time)->format('d-m-Y') : 'N/A' }}
                                    <br>
                                    {{ $pending_monthly_expenses->verified_time ? \Carbon\Carbon::parse($pending_monthly_expenses->verified_time)->format('h:i A') : 'N/A' }}
                        </td>

                        <td style="padding: 10px; min-width: 200px;">Approved by<br>
                            {{ $pending_monthly_expenses->approvedBy?->name ?? 'N/A' }}<br>

                                    {{ $pending_monthly_expenses->approved_time ? \Carbon\Carbon::parse($pending_monthly_expenses->approved_time)->format('d-m-Y') : 'N/A' }}
                                    <br>
                                    {{ $pending_monthly_expenses->approved_time ? \Carbon\Carbon::parse($pending_monthly_expenses->approved_time)->format('h:i A') : 'N/A' }}
                        </td>
                    </tr>
                </table>

            </div>
            
        </div>
    </div>
</body>
</html>