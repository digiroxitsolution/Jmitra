<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Expense</title>
</head>
<body>
    <div style="width: 100%;">
        <div style="margin-top: 3rem; margin-bottom: 3rem; border: 1px solid #e0e0e0;">
            <div style="border: 1px solid #e0e0e0; background-color: #f8f9fa; padding: 1rem; border-radius: 0.5rem 0.5rem 0 0;">
                <h2 style="color: #ffffff; background-color: #049DD9; padding: 0.5rem; border-radius: 0.25rem;">General Expense</h2>
            </div>
            <div style="background-color: #ffffff; padding: 2rem;">
                <div style="text-align: center; font-size: 25px;">
                    <h5 style="text-decoration: underline; margin-bottom: 1.5rem;">@if(isset($monthName) && $monthName)
                                ( {{ \Carbon\Carbon::parse($monthName)->format('F Y') }} )
                            @endif
                            @if(isset($fromDate) && isset($toDate) && $fromDate && $toDate)
                                ( {{ \Carbon\Carbon::parse($fromDate)->format('j F, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('j F, Y') }})
                            @endif</h5>
                    <h6 style="text-decoration: underline; margin-bottom: 1.5rem;">General Expense</h6>
                    <button style="background-color: #17a2b8; color: #ffffff; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">{{ $all_total_genex }}</button>
                    <div style="display: flex; justify-content: center; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <button style="background-color: #17a2b8; color: #ffffff; border: none; width: 2rem; height: 2rem; border-radius: 20%;"></button>
                        <h5 style="text-decoration: underline;">General Expenses</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</body>
</html>