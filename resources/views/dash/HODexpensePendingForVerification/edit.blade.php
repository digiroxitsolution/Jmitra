<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fontawsome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery CDN (required for AJAX) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Normal CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Favicon Icon -->
    <link rel="icon" href="{{ asset('assets/images/Favicon.png') }}">
    <title>@yield('title', config('app.name', 'Jmitra & Co. Pvt. Ltd'))</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCkXM3qxBSkyQDCrmTq1wDL3ZSXXi8nQ7Y&libraries=places"
        async></script>
    <style>
        td:hover {
            background-color: #f9f9f9;
        }

        td:hover .hover-box {
            display: flex;
            flex-direction: column;
        }

        .hover-box {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 40px;
            border: 1px solid #ccc;
            background-color: #077fad;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .hover-box .left,
        .hover-box .right {
            flex: 1;
            padding: 10px;
            border-right: 1px solid #ddd;
        }

        .hover-box .right {
            border-right: none;
        }
    </style>
    <style>
        .scrollable-card-body {
            white-space: nowrap;
            overflow-x: hidden;
            /* Remove inner scrollbar */
        }

        .sticky-scrollbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .fake-scrollbar {
            height: 20px;
            overflow-x: auto;

        }

        .fake-scrollbar-content {
            height: 1px;

        }

        .scrollable-table-container {
            overflow-x: auto;
            /* Allow horizontal scrolling */
            max-width: 100vw;
            /* Adjust width as needed */
        }

        /* Ensure the table header stays fixed */
        .table thead tr {
            position: sticky;
            top: 0;
            background: white;
            /* Ensure header is not transparent */
            z-index: 100;
            /* Keep it above other elements */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            /* Optional shadow for better visibility */
        }
    </style>
    <style>
        .is-invalid {
            border-color: #dc3545;
            background-color: #f8d7da;
        }
    </style>


</head>

<body>
    <div class="d-flex" id="wrapper">


        <!-- Page Content + Nav Bar -->
        <div id="page-content-wrapper">
            <!--*******************
   Navbar start
  *******************-->

            <!--*******************
   Navbar end
  *******************-->
            <!--*******************
   Main Content start
  *******************-->


            <!-- <pre>{{ json_encode($monthly_expense_histories, JSON_PRETTY_PRINT) }}</pre> -->
            <div class="row">

                <!--*******************
     Form Submit of Selected Month Start
    *****************-->
                <div class="card">
                    <div class="card-dialog card-dialog-centered card-fullscreen">

                        <div class="card-header" style="background-color:#077fad; color:white;">
                            <h4 class="" style="height: 50px;">Monthly Expenses Report
                                <a
                                    href="{{ route('pending_expense_verification_print', $pending_monthly_expenses->id) }}"><button
                                        type="submit" class="btn btn-success float-end"><i
                                            class="fa-solid fa-download"></i></button></a>
                                <!-- <form action="{{ route('print_user_monthly_expense') }}"method="POST">
 @csrf
 <input type="month" name="formSubmitSelectedMonth" id="formSubmitSelectedMonth" class="form-control" placeholder="Select month" value="{{ date('Y-m') }}" hidden>
        <button type="submit" class="btn btn-success float-end"><i class="fa-solid fa-download"></i></button>
       </form> -->




                        </div>
                        <div class="card-body">
                            @include('includes.message')
                            <div class="row mb-4">
                                <div class="col-lg-4 col-12">
                                    Compnay Name: {{ $user->userDetail->companyMaster->company_name }}
                                </div>
                                <div class="col-lg-4 col-12">
                                    Division: {{ $user->userDetail->DivisonMaster->name }}
                                </div>
                                <div class="col-lg-4 col-12">
                                    Month:
                                    {{ \Carbon\Carbon::parse($pending_monthly_expenses->expense_date)->format('F') }}

                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-lg-4 col-12">
                                    Name: {{ $user->name }}
                                </div>
                                <div class="col-lg-4 col-12">
                                    Designation: {{ $user->userDetail->designation->name }}
                                </div>
                                <div class="col-lg-4 col-12">
                                    Location: {{ $user->userDetail->locationMaster->working_location }}
                                </div>
                            </div>
                            <div class="card-body scrollable-card-body" id="scrollable-content">
                                <!-- Extra-wide content to guarantee horizontal scrolling -->
                                <div style="width: 3000px; background-color: lightblue;">
                                    <table class="table table-bordered table-hover text-center table-light"
                                        style="width: 2982px;">
                                        <thead style="position: sticky !important; top: 0% !important;">
                                            <tr class="table-active fw-bold"
                                                style="position: sticky !important; top: 0% !important;">
                                                <th rowspan="2">Date</th>
                                                <th rowspan="2">Day</th>
                                                <th rowspan="2"style="width: 10%">Expense Type</th>
                                                <th rowspan="2" style="width: 10%">From</th>
                                                <th rowspan="2" style="width: 10%">To</th>
                                                <th colspan="2">Time</th>
                                                <th rowspan="2" style="width: 6%">KM as per User</th>
                                                <th rowspan="2" style="width: 6%">KM as per Google</th>
                                                <th rowspan="2" style="width: 8%">Mode</th>
                                                <th colspan="2" style="width: 5%">Fare Amount</th>
                                                <th colspan="2">Daily Allowances</th>
                                                <th rowspan="2">Postage</th>
                                                <th rowspan="2">TEL/TGM</th>
                                                <th rowspan="2">Print Stationary</th>

                                                <th colspan="2" style="width: 10%">Other Expenses</th>
                                                <th rowspan="2">Attendace</th>
                                            </tr>
                                            <tr class="table-active fw-bold">
                                                <th>DEP</th>
                                                <th>ARR</th>
                                                <th>Choose To Apply</th>
                                                <th>Fare Amount</th>

                                                <th>Working</th>
                                                <th>N. Working</th>
                                                <th style="width: 6%">Purpose</th>
                                                <th style="width: 4%">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @can('monthly-pending-expense-edit')
                                                <form id="monthlyExpenseForm"
                                                    action="{{ route('pending_expense_verification_update.update', $pending_monthly_expenses->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <input type="number" id="pending_monthly_expense_id"
                                                        value="{{ $pending_monthly_expenses->id }}"
                                                        name="pending_monthly_expense_id" hidden>

                                                    @foreach ($monthly_expenses as $key => $monthly_expense)
                                                        <tr @if ($monthly_expense->history_diff) class="hover-history" @endif>
                                                            <!-- <td> -->
                                                            <input type="hidden" id="expense_id_{{ $key }}"
                                                                value="{{ $monthly_expense->id }}"
                                                                name="monthly_expense[{{ $key }}][id]">
                                                            <!-- </td> -->
                                                            <td class="history-cell">
                                                                <input class="form-control" type="date"
                                                                    id="expense_date_{{ $key }}"
                                                                    value="{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('Y-m-d') }}"
                                                                    class="form-control no-border"
                                                                    name="monthly_expense[{{ $key }}][expense_date]">


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->expense_date != $history->expense_date)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ \Carbon\Carbon::parse($history->expense_date)->format('d-m-Y') }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->expense_date != $history->expense_date)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ \Carbon\Carbon::parse($history->expense_date)->format('d-m-Y') }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                            </td>
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('l') }}
                                                            </td>

                                                            <td>
                                                                {{ $monthly_expense->ExpenseTypeMaster?->expense_type ?? 'N/A' }}
                                                            </td>
                                                            <td
                                                                class="history-cell @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->from != $history->from)
                                                            edited
                                                                
                                                        @endif @endforeach   ">
                                                                <input type="text" id="from_{{ $key }}"
                                                                    value="{{ $monthly_expense->from }}"
                                                                    class="form-control no-border"
                                                                    name="monthly_expense[{{ $key }}][from]">


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->from != $history->from)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->from }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->from != $history->from)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->from }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach



                                                            </td>
                                                            <td
                                                                class="history-cell
                                                        @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->to != $history->to)
                                                            edited
                                                        @endif @endforeach
                                                    ">
                                                                <input type="text" id="to_{{ $key }}"
                                                                    value="{{ $monthly_expense->to }}"
                                                                    class="form-control no-border"
                                                                    name="monthly_expense[{{ $key }}][to]">

                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->to != $history->to)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->to }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->to != $history->to)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->to }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            <td
                                                                class="history-cell
                                                        @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->departure_time != $history->departure_time)
                                                            edited
                                                        @endif @endforeach
                                                    ">
                                                                <input class="form-control" type="datetime-local"
                                                                    id="departure_time_{{ $key }}"
                                                                    value="{{ $monthly_expense->departure_time ? \Carbon\Carbon::parse($monthly_expense->departure_time)->format('Y-m-d\TH:i') : '' }}"
                                                                    class="form-control no-border"
                                                                    name="monthly_expense[{{ $key }}][departure_time]">


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->departure_time != $history->departure_time)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->departure_time }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->departure_time != $history->departure_time)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->departure_time }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                            </td>
                                                            <td
                                                                class="history-cell
                                                        @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->arrival_time != $history->arrival_time)
                                                            edited 
                                                        @endif @endforeach
                                                    ">
                                                                <input class="form-control" type="datetime-local"
                                                                    id="arrival_time_{{ $key }}"
                                                                    value="{{ $monthly_expense->arrival_time ? \Carbon\Carbon::parse($monthly_expense->arrival_time)->format('Y-m-d\TH:i') : '' }}"
                                                                    class="form-control no-border"
                                                                    name="monthly_expense[{{ $key }}][arrival_time]">

                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->arrival_time != $history->arrival_time)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->arrival_time }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->arrival_time != $history->arrival_time)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->arrival_time }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                            </td>

                                                            <td
                                                                class="history-cell
                                                    @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->km_as_per_user != $history->km_as_per_user)
                                                            edited
                                                        @endif @endforeach
                                                    ">
                                                                <input type="number"
                                                                    id="km_as_per_user_{{ $key }}"
                                                                    value="{{ $monthly_expense->km_as_per_user }}"
                                                                    class="form-control no-border no-negative"
                                                                    name="monthly_expense[{{ $key }}][km_as_per_user]">



                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->km_as_per_user != $history->km_as_per_user)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->km_as_per_user }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->km_as_per_user != $history->km_as_per_user)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->km_as_per_user }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                            </td>
                                                            <td
                                                                class="history-cell
                                                        @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->km_as_per_google_map != $history->km_as_per_google_map)
                                                            edited
                                                        @endif @endforeach
                                                    ">
                                                                <input type="number"
                                                                    id="km_as_per_google_map_{{ $key }}"
                                                                    value="{{ $monthly_expense->km_as_per_google_map }}"
                                                                    class="form-control no-border no-negative"
                                                                    name="monthly_expense[{{ $key }}][km_as_per_google_map]"
                                                                    readonly>



                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->km_as_per_google_map != $history->km_as_per_google_map)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->km_as_per_google_map }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->km_as_per_google_map != $history->km_as_per_google_map)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->km_as_per_google_map }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach





                                                            </td>
                                                            <td
                                                                class="history-cell
                                                        @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->mode_of_expense_master_id != $history->mode_of_expense_master_id)
                                                        @if ($history->mode_of_expense_master_id != null)
                                                            edited
                                                        @endif  
                                                        @endif @endforeach
                                                    ">
                                                                <select class="form-select no-border"
                                                                    aria-label="Default select example"
                                                                    id="mode_expense_{{ $key }}"
                                                                    name="monthly_expense[{{ $key }}][mode_expense]">
                                                                    <option value="" disabled selected>Select Expense
                                                                        Mode</option>
                                                                    @foreach ($expense_modes as $expense_mode)
                                                                        <option value="{{ $expense_mode->id }}"
                                                                            {{ $expense_mode->id == $monthly_expense->mode_of_expense_master_id ? 'selected' : '' }}>
                                                                            {{ $expense_mode->mode_expense }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->mode_of_expense_master_id != $history->mode_of_expense_master_id)
                                                                        @if ($history->mode_of_expense_master_id != null)
                                                                            <div class="hover-box">
                                                                                <div class="left">
                                                                                    {{ $history->ModeofExpenseMaster->mode_expense }}
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->mode_of_expense_master_id != $history->mode_of_expense_master_id)
                                                                        @if ($history->mode_of_expense_master_id != null)
                                                                            <div class="hover-box">
                                                                                <div class="right">
                                                                                    {{ $history->ModeofExpenseMaster->mode_expense }}
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endforeach




                                                            </td>
                                                            <td>
                                                                <select id="calculates_fare_{{ $key }}"
                                                                    name="calculates_fare{{ $key }}"
                                                                    class="form-select">
                                                                    <option selected disabled>Select</option>
                                                                    <option value="by user">KM as per User</option>
                                                                    <option value="by google map">KM as per Google Map
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td
                                                                class="history-cell
                                                        @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->fare_amount != $history->fare_amount)
                                                        edited
                                                        @endif @endforeach
                                                    ">
                                                                <input type="number"
                                                                    id="fare_amount_{{ $key }}"
                                                                    value="{{ $monthly_expense->fare_amount }}"
                                                                    class="form-control no-border no-negative"
                                                                    name="monthly_expense[{{ $key }}][fare_amount]">


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->fare_amount != $history->fare_amount)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->fare_amount }}</div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->fare_amount != $history->fare_amount)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->fare_amount }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach



                                                            </td>

                                                            <td
                                                                class="history-cell
                                                        @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->da_total != $history->da_total)
                                                            edited
                                                        @endif @endforeach
                                                    ">
                                                                <input type="number" id="da_total_{{ $key }}"
                                                                    value="{{ $monthly_expense->expense_type_master_id != 8 ? $monthly_expense->da_total : '' }}"
                                                                    class="form-control no-border no-negative"
                                                                    name="monthly_expense[{{ $key }}][da_total]">


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->da_total != $history->da_total)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->da_total }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->da_total != $history->da_total)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->da_total }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach


                                                            </td>
                                                            <td
                                                                class="history-cell
                                                    @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->da_total != $history->da_total)
                                                            edited
                                                        @endif @endforeach
                                                    ">
                                                                <input type="number" id="da_total_{{ $key }}"
                                                                    value="{{ $monthly_expense->expense_type_master_id == 8 ? $monthly_expense->da_total : '' }}"
                                                                    class="form-control no-border no-negative"
                                                                    name="monthly_expense[{{ $key }}][expense_type_master_id]">


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->da_total != $history->da_total)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->da_total }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->da_total != $history->da_total)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->da_total }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach




                                                            </td>

                                                            <td
                                                                class="history-cell
                                                    @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->postage != $history->postage)
                                                            edited
                                                        @endif @endforeach
                                                    ">
                                                                <input type="number" id="postage_{{ $key }}"
                                                                    value="{{ $monthly_expense->postage }}"
                                                                    class="form-control no-border no-negative"
                                                                    name="monthly_expense[{{ $key }}][postage]">


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->postage != $history->postage)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->postage }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->postage != $history->postage)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->postage }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach


                                                            </td>
                                                            <td
                                                                class="history-cell
                                                    @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->mobile_internet != $history->mobile_internet)
                                                            edited
                                                        @endif @endforeach">
                                                                <input type="number"
                                                                    id="mobile_internet_{{ $key }}"
                                                                    value="{{ $monthly_expense->mobile_internet }}"
                                                                    class="form-control no-border no-negative"
                                                                    name="monthly_expense[{{ $key }}][mobile_internet]">
                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->mobile_internet != $history->mobile_internet)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->mobile_internet }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->mobile_internet != $history->mobile_internet)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->mobile_internet }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach


                                                            </td>
                                                            <td
                                                                class="history-cell
                                                    @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->print_stationery != $history->print_stationery)
                                                            edited 
                                                        @endif @endforeach
                                                        ">
                                                                <input type="text"
                                                                    id="print_stationery{{ $key }}"
                                                                    value="{{ $monthly_expense->print_stationery }}"
                                                                    class="form-control no-border"
                                                                    name="monthly_expense[{{ $key }}][print_stationery]">


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->print_stationery != $history->print_stationery)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->print_stationery }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->print_stationery != $history->print_stationery)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->print_stationery }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach




                                                            </td>
                                                            <td
                                                                class="history-cell
                                                        @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->other_expense_master_id != $history->other_expense_master_id)
                                                        @if ($history->other_expense_master_id != null)
                                                            edited
                                                        @endif
                                                        @endif @endforeach
                                                    ">
                                                                <select class="form-select"
                                                                    aria-label="Default select example"
                                                                    id="other_expense_{{ $key }}"
                                                                    name="monthly_expense[{{ $key }}][other_expense]">
                                                                    <option value="" disabled selected>Select Other
                                                                        Expenses Purpose</option>

                                                                    @foreach ($other_expense_master as $other_expense)
                                                                        <option value="{{ $other_expense->id }}"
                                                                            {{ $other_expense->id == $monthly_expense->other_expense_master_id ? 'selected' : '' }}>
                                                                            {{ $other_expense->other_expense }}
                                                                        </option>
                                                                    @endforeach

                                                                </select>


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->other_expense_master_id != $history->other_expense_master_id)
                                                                        @if ($history->other_expense_master_id != null)
                                                                            <div class="hover-box">
                                                                                <div class="left">
                                                                                    {{ $history->OtherExpenseMaster->other_expense }}
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->other_expense_master_id != $history->other_expense_master_id)
                                                                        @if ($history->other_expense_master_id != null)
                                                                            <div class="hover-box">
                                                                                <div class="left">
                                                                                    {{ $history->OtherExpenseMaster->other_expense }}
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endforeach





                                                            </td>
                                                            <td
                                                                class="history-cell
                                                    @foreach ($monthly_expense_histories[$key] as $history)
                                                        @if ($monthly_expense->other_expenses_amount != $history->other_expenses_amount)
                                                            edited 
                                                        @endif @endforeach
                                                        ">
                                                                <input type="text"
                                                                    id="other_expenses_amount_{{ $key }}"
                                                                    value="{{ $monthly_expense->other_expenses_amount }}"
                                                                    class="form-control no-border"
                                                                    name="monthly_expense[{{ $key }}][other_expenses_amount]">


                                                                @foreach ($monthly_expense_histories[$key] as $history)
                                                                    @if ($monthly_expense->other_expenses_amount != $history->other_expenses_amount)
                                                                        <div class="hover-box">
                                                                            <div class="left">
                                                                                {{ $history->other_expenses_amount }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                @foreach ($monthly_expense_histories2[$key] as $history)
                                                                    @if ($monthly_expense->other_expenses_amount != $history->other_expenses_amount)
                                                                        <div class="hover-box">
                                                                            <div class="right">
                                                                                {{ $history->other_expenses_amount }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach




                                                            </td>



                                                            <td class="table-active">
                                                                <a href="javascript:void(0);"
                                                                    data-url="{{ route('get_other_attendance', ['id' => $user->id, 'attendance_date' => $monthly_expense->expense_date]) }}"
                                                                    class="view-attendance-btn">
                                                                    <button type="button"
                                                                        class="btn btn-info float-end ms-2 text-white">
                                                                        View
                                                                    </button>
                                                                </a>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <button type="submit" id="hiddenSubmitButton" hidden>My
                                                        Button</button>
                                                </form>

                                            @endcan


                                            <!-- <tr>
                                                    <td colspan="8" class="table-active">Total</td>
                                                    <td>{{ $total_fare_amount }}</td>
                                                    <td>{{ $total_da_location_working }}</td>
                                                    <td>{{ $total_da_location_not_working }}</td>
                                                    <td>{{ $total_postage }}</td>
                                                    <td>{{ $total_mobile_internet }}</td>
                                                    <td>{{ $total_other_expense_purpose }}</td>
                                                    <td>{{ $total_other_expense_amount }}</td>
                                                    <td>{{ $total_print_stationery }}</td>
                                                    <td>***</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7"></td>
                                                    <td class="table-active">RUPEES in Words</td>
                                                    <td colspan="4">{{ $grand_total_in_words }}</td>
                                                    <td class="table-active">Grand Total</td>
                                                    <td colspan="3">{{ $grand_total }}</td>
                                                </tr> -->

                                            <tr class="table-active">
                                                <td colspan="9" style="width: 8%" class="fw-bold text-end">Total
                                                </td>
                                                <td><strong>Fare:</strong> <span id="totalFare">₹ 0.00</span></td>
                                                <td colspan="2"><strong>Working:</strong> <span id="totalWorking">₹
                                                        0.00</span></td>

                                                <td><strong>Postage:</strong> <span id="totalPostage">₹ 0.00</span>
                                                </td>
                                                <td><strong>Tel/TGM:</strong> <span id="totaltel_tgm">₹ 0.00</span>
                                                </td>
                                                <td></td>
                                                <td><strong>Amount:</strong> <span id="totalAmount">₹ 0.00</span></td>
                                                <td><strong>Stationery:</strong> <span id="totalStationery">₹
                                                        0.00</span></td>
                                                <td></td>
                                            </tr>
                                            <tr class="table-active">
                                                <td colspan="9" class="fw-bold text-end">RUPEES in Words</td>
                                                <td colspan="5"><span id="grand_total_in_word"></span></td>
                                                <td colspan="2" class="fw-bold">Grand Total</td>
                                                <td colspan="2"><span id="grand_total">₹ 0.00</span></td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <!-- <div class="row mb-4">
        <div class="col-lg-6 col-12 fw-bold">Signature of Field Staff: __________ __________ __________</div>
        <div class="col-lg-6 col-12 fw-bold">Signature of Manager: __________ __________ __________</div>
       </div> -->    
      
                            @if (!is_null($reasonof_update))
                                <div class="row mb-3">
                                    @foreach ($reasonof_update as $rup )
                                    <div class="col">
                                        <p class="text-muted mb-1">
                                            <strong>Updated By:</strong>
                                            {{ $rup->showUsers->name ?? 'N/A' }}
                                        </p>
                                        <p class="text-muted">
                                            <strong>Reason for Update:</strong> {{ $rup->reason??''}}
                                        </p>
                                    </div>
                                    @endforeach
                                   
                                </div>
                            @endif

                            <div class="row mb-4">
                                <div class="form-check">
                                    <input class="form-check-input me-2 ms-1" type="checkbox" value="1"
                                        id="accept_policy" name="accept_policy"
                                        {{ $pending_monthly_expenses->accept_policy == 1 ? 'checked' : '' }}
                                        {{ $pending_monthly_expenses->is_submitted === 1 ? 'disabled' : '' }}>
                                    <label class="form-check-label text-muted" for="accept_policy">
                                        I hereby confirmed that I verified the Expenses and found OK as per Travel/
                                        Daily Allowance Policy.
                                    </label>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 col-12">Submitted by<br>
                                    {{ $pending_monthly_expenses->user?->name }}<br>

                                    {{ $pending_monthly_expenses->expense_date ? \Carbon\Carbon::parse($pending_monthly_expenses->expense_date)->format('d-m-Y') : 'N/A' }}
                                    <br>
                                    {{ $pending_monthly_expenses->expense_date ? \Carbon\Carbon::parse($pending_monthly_expenses->expense_date)->format('h:i A') : 'N/A' }}
                                </div>
                                <div class="col-lg-4 col-12">Verified by<br>
                                    {{ $pending_monthly_expenses->verifiedBy?->name }}<br>

                                    {{ $pending_monthly_expenses->verified_time ? \Carbon\Carbon::parse($pending_monthly_expenses->verified_time)->format('d-m-Y') : 'N/A' }}
                                    <br>
                                    {{ $pending_monthly_expenses->verified_time ? \Carbon\Carbon::parse($pending_monthly_expenses->verified_time)->format('h:i A') : 'N/A' }}
                                </div>
                                <div class="col-lg-4 col-12">Approved
                                    by<br>{{ $pending_monthly_expenses->approvedBy?->name }}<br>

                                    {{ $pending_monthly_expenses->approved_time ? \Carbon\Carbon::parse($pending_monthly_expenses->approved_time)->format('d-m-Y') : 'N/A' }}
                                    <br>
                                    {{ $pending_monthly_expenses->approved_time ? \Carbon\Carbon::parse($pending_monthly_expenses->approved_time)->format('h:i A') : 'N/A' }}
                                </div>
                            </div>

                            <div class="card-footer" style="height: 50px;">
                                <a href="{{ route('pending_expense_verification.index') }}"><button type="button"
                                        class="btn btn-danger float-end ms-2"
                                        data-bs-dismiss="modal">Closed</button></a>
                                @can('monthly-pending-expense-reject')
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#reasonOfRejectedModal"
                                        class="btn btn-danger float-end ms-2">Rejected</button>
                                @endcan

                                @can('monthly-pending-expense-reopen')
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#reasonOfReOpenModal"
                                        class="btn btn-warning text-white float-end ms-2">Re-Open</button>
                                @endcan
                                {{-- 
                                @can('monthly-pending-expense-update')
                                    <button type="button" class="btn btn-info float-end ms-2 text-white"
                                        id="externalSubmitButton">Update</button>
                                @endcan --}}
                                @can('monthly-pending-expense-update')
                                    <button type="button" class="btn btn-info float-end ms-2 text-white"
                                        data-bs-toggle="modal" data-bs-target="#reasonOfUpdateModal">Update</button>
                                @endcan


                                @can('monthly-pending-expense-verify')
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#submissionPromisiingModal"
                                        class="btn btn-success float-end ms-2">Verify & Submit</button>
                                @endcan

                                @can('monthly-pending-expense-approve')
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#submissionPromisiingModal"
                                        class="btn btn-success float-end ms-2">Approve & Submit</button>
                                @endcan


                            </div>

                        </div>
                    </div>
                    <!--*******************
     Form Submit of Selected Month Start
    *****************-->

                    <!--*******************
     Submission Promising Start
    *****************-->
                    <div class="modal" tabindex="-1" id="submissionPromisiingModal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Submission Promising</h5>
                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Please Re-Check your work before Final Submission. After Final Submission you
                                        will not be able to modify it.</h6>
                                </div>
                                <div class="modal-footer">
                                    @can('monthly-pending-expense-approve')
                                        <a
                                            href="{{ route('pending_expense_verification.changeStatus', ['id' => $pending_monthly_expenses->id, 'status' => 1]) }}">
                                            <button type="button" class="btn btn-success float-end ms-2"
                                                {{ $pending_monthly_expenses->is_approve === 1 ? 'disabled' : '' }}>Final
                                                Approve & Submit</button>
                                        </a>
                                    @endcan
                                    @can('monthly-pending-expense-verify')
                                        <a
                                            href="{{ route('pending_expense_verification.changeStatus', ['id' => $pending_monthly_expenses->id, 'status' => 2]) }}">
                                            <button type="button" class="btn btn-success float-end ms-2">Verify &
                                                Submit</button>
                                        </a>
                                    @endcan

                                </div>
                            </div>
                        </div>
                    </div>

                    <!--*******************
 update reason modal start
    *****************-->

                    <div class="modal" tabindex="-1" id="reasonOfUpdateModal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Reason for Update</h5>
                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="update_reason" class="form-label">Please provide a reason for the
                                            update:</label>
                                        <textarea class="form-control" id="update_reason" rows="3" name="update_reason_text" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="confirmUpdateSubmit">Submit
                                        Update</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--*******************
     update reason modal end
    *****************-->
                    <!--*******************
     Submission Promising End
    *****************-->
                    @can('monthly-pending-expense-reject')
                        <!--*******************
                             Reason of Rejected Start
                            *****************-->
                        <div class="modal" tabindex="-1" id="reasonOfRejectedModal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reason of Rejected</h5>
                                        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('pending_expense_reject', $pending_monthly_expenses->id) }}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <label for="rejection_master_id">Reason for Rejection</label>
                                            <select name="rejection_master_id" id="rejection_master_id"
                                                class="form-select" aria-label="Select reason for rejection" required>
                                                <option value="" selected disabled>Select Reason</option>
                                                @foreach ($reason_of_rejections as $reason_of_rejection)
                                                    <option value="{{ $reason_of_rejection->id }}">
                                                        {{ $reason_of_rejection->reason_of_rejection }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--*******************
                             Reason of Rejected End
                            *****************-->
                    @endcan
                    <!--*******************
     Reason of Re-Open Start
    *****************-->
                    <div class="modal" tabindex="-1" id="reasonOfReOpenModal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Reason of Re-Open</h5>
                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('pending_expense_re_open', $pending_monthly_expenses->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label for="re_open_master_id">Reason of Re-Open</label>
                                        <select name="re_open_master_id" id="re_open_master_id" class="form-select"
                                            aria-label="Default select example" required>
                                            <option selected>Select Reason of Re-Open</option>
                                            @foreach ($reason_of_re_opens as $reason_of_re_open)
                                                <option value="{{ $reason_of_re_open->id }}">
                                                    {{ $reason_of_re_open->reason_of_re_open }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="modal-footer">

                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--*******************
     Reason of Re-Open End
    *****************-->

                    @include('dash.HODexpensePendingForVerification.viewAttendance')

                </div>
            </div>
        </div>

        <!-- Sticky fake scrollbar -->
        <div class="sticky-scrollbar">
            <div class="fake-scrollbar" id="fake-scrollbar">
                <div class="fake-scrollbar-content" id="fake-scrollbar-content"></div>
            </div>
        </div>



        <!-- Bootstrap JS -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
        </script>
        <!-- Normal JS -->
        <script src="{{ asset('assets/js/script.js') }}"></script>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <!-- DataTables Bootstrap JS (Optional) -->
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>



        <script>
            const scrollableContent = document.getElementById('scrollable-content');
            const fakeScrollbar = document.getElementById('fake-scrollbar');
            const fakeScrollbarContent = document.getElementById('fake-scrollbar-content');

            function syncScroll() {
                fakeScrollbar.scrollLeft = scrollableContent.scrollLeft;
            }

            function syncFakeScroll() {
                scrollableContent.scrollLeft = fakeScrollbar.scrollLeft;
            }

            function updateFakeScrollbar() {
                fakeScrollbarContent.style.width = scrollableContent.scrollWidth + 'px';
            }

            scrollableContent.addEventListener('scroll', syncScroll);
            fakeScrollbar.addEventListener('scroll', syncFakeScroll);

            window.addEventListener('resize', updateFakeScrollbar);
            window.addEventListener('load', updateFakeScrollbar);
        </script>
        {{-- // new script from avinahs start  --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Updated: Select the main form by its new ID
                const mainForm = document.getElementById('monthlyExpenseForm');
                const updateButton = document.querySelector('[data-bs-target="#reasonOfUpdateModal"]');
                const updateReasonTextarea = document.getElementById('update_reason');
                const reasonOfUpdateModal = document.getElementById('reasonOfUpdateModal');

                console.log('Debugging Elements (on DOMContentLoaded):');
                console.log('mainForm:', mainForm); // Check if form is now found
                console.log('updateButton:', updateButton);
                console.log('updateReasonTextarea:', updateReasonTextarea);
                console.log('reasonOfUpdateModal:', reasonOfUpdateModal);

                if (updateButton && updateReasonTextarea && mainForm && reasonOfUpdateModal) {
                    console.log(
                        'All essential elements for update functionality found. Setting up delegated event listener...'
                    );

                    reasonOfUpdateModal.addEventListener('click', function(event) {
                        const clickedSubmitButton = event.target.closest('#confirmUpdateSubmit');

                        if (clickedSubmitButton) {
                            console.log('Submit Update button clicked via delegation (using closest)!');

                            const reason = updateReasonTextarea.value.trim();

                            if (reason === '') {
                                alert('Please provide a reason for the update.');
                                return;
                            }

                            // Create a hidden input to hold the reason and append it to the form
                            let hiddenReasonInput = document.createElement('input');
                            hiddenReasonInput.type = 'hidden';
                            hiddenReasonInput.name = 'update_reason';
                            hiddenReasonInput.value = reason;
                            mainForm.appendChild(hiddenReasonInput);

                            console.log('Reason added to form as hidden input:', reason);
                            mainForm.submit();
                            console.log('Main form submitted.');

                            const modalInstance = bootstrap.Modal.getInstance(reasonOfUpdateModal);
                            if (modalInstance) {
                                modalInstance.hide();
                                console.log('Modal closed.');
                            }
                        }
                    });

                } else {
                    console.error(
                        'ERROR: One or more essential elements for the update functionality were NOT found on page load. Check the HTML IDs and selectors.'
                    );
                    console.log('Final check: mainForm:', mainForm);
                    console.log('Final check: updateButton:', updateButton);
                    console.log('Final check: updateReasonTextarea:', updateReasonTextarea);
                    console.log('Final check: reasonOfUpdateModal:', reasonOfUpdateModal);
                }

                // --- Existing checkbox and submit button logic (adjusted for externalSubmissionButton) ---
                const checkbox = document.getElementById('accept_policy');
                // Assuming 'externalSubmissionButton' is the ID of the button you want to control with the checkbox.
                // If you have multiple buttons for submission/verification, clarify which one this controls.
                const externalSubmissionButton = document.getElementById('externalSubmissionButton');
                // If you are instead controlling the submissionPromisiingModal button, use this:
                // const verifySubmitButton = document.querySelector('[data-bs-target="#submissionPromisiingModal"]');


                // Ensure the button variable is the correct one for the checkbox logic
                const buttonToControl = externalSubmissionButton; // Or verifySubmitButton if that's the one

                if (checkbox && buttonToControl) {
                    toggleSubmitButtonState(); // Call on initial load

                    checkbox.addEventListener('change', function() {
                        toggleSubmitButtonState();
                    });

                    function toggleSubmitButtonState() {
                        const isSubmitted = parseInt("{{ $pending_monthly_expenses->is_submitted ?? 0 }}");
                        if (!checkbox.checked || isSubmitted === 1) {
                            buttonToControl.disabled = true;
                        } else {
                            buttonToControl.disabled = false;
                        }
                    }
                } else {
                    console.warn('Checkbox or button for accept_policy not found. Check their IDs.');
                }
            });
        </script>

        {{-- 
// new script from avinash end --}}
        {{-- <script>
            // comment by avinash
            // document.getElementById('externalSubmitButton').addEventListener('click', function() {
            //     // Trigger the submit button inside the form
            //     document.getElementById('hiddenSubmitButton').click();
            // });
            document.addEventListener('DOMContentLoaded', function() {
                const checkbox = document.getElementById('accept_policy');
                const submitButton = document.getElementById('externalSubmissionButton');

                // Check the initial state based on checkbox value
                toggleSubmitButtonState();

                // Add event listener to enable/disable button when checkbox is changed
                checkbox.addEventListener('change', function() {
                    toggleSubmitButtonState();
                });

                // Function to enable/disable the submit button based on checkbox state
                function toggleSubmitButtonState() {
                    // If the checkbox is unchecked or form is submitted, disable the button
                    if (!checkbox.checked || {{ $pending_monthly_expenses->is_submitted }} === 1) {
                        submitButton.disabled = true;
                    } else {
                        submitButton.disabled = false;
                    }
                }
            });
        </script> --}}

        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                const attendanceModal = document.getElementById('viewOfAttendenceModal');
                const modalBodyTable = attendanceModal.querySelector('tbody');

                document.querySelectorAll('.view-attendance-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const url = this.getAttribute('data-url');

                        // Clear the modal content
                        modalBodyTable.innerHTML = '';

                        // Fetch the attendance data
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    modalBodyTable.innerHTML =
                                        `<tr><td colspan="7" class="text-center">${data.error}</td></tr>`;
                                } else {
                                    data.forEach(attendance => {
                                        const formatDateTime = (dateTimeString) => {
                                            if (!dateTimeString) return 'N/A';

                                            const date = new Date(dateTimeString);
                                            if (isNaN(date.getTime()))
                                                return 'Invalid Date';

                                            // Manually extract and format day, month, and year
                                            const day = String(date.getDate()).padStart(
                                                2, '0'); // Ensure two digits
                                            const month = String(date.getMonth() + 1)
                                                .padStart(2, '0'); // Months are 0-based
                                            const year = date
                                                .getFullYear(); // Get full 4-digit year

                                            // Format time in 12-hour format with AM/PM
                                            let hours = date.getHours();
                                            const minutes = String(date.getMinutes())
                                                .padStart(2, '0');
                                            const amPm = hours >= 12 ? 'PM' : 'AM';
                                            hours = hours % 12 ||
                                                12; // Convert 24-hour format to 12-hour

                                            return `${day}-${month}-${year} ${hours}:${minutes} ${amPm}`;
                                        };


                                        const checkIn = formatDateTime(attendance.check_in);
                                        const checkOut = formatDateTime(attendance
                                            .check_out);
                                        const checkInCheckOut = checkIn !== 'N/A' &&
                                            checkOut !== 'N/A' ?
                                            `${checkIn} - ${checkOut}` : 'N/A';

                                        let duration = 'N/A';
                                        if (checkIn !== 'N/A' && checkOut !== 'N/A') {
                                            const checkInDate = new Date(attendance
                                                .check_in);
                                            const checkOutDate = new Date(attendance
                                                .check_out);

                                            // Calculate duration in milliseconds
                                            const timeDifference = checkOutDate -
                                                checkInDate;

                                            // Convert milliseconds to hours and minutes
                                            if (timeDifference > 0) {
                                                const hours = Math.floor(timeDifference / (
                                                    1000 * 60 * 60));
                                                const minutes = Math.floor((timeDifference %
                                                    (1000 * 60 * 60)) / (1000 * 60));
                                                duration = `${hours} hr ${minutes} min`;
                                            } else {
                                                duration =
                                                    'Invalid duration'; // If check-out is earlier than check-in
                                            }
                                        }

                                        const row = `
                                <tr>
                                    <td>${attendance.customer_name || 'N/A'}</td>
                                    <td>${attendance.customer_type || 'N/A'}</td>
                                    <td>${checkIn}</td>
                                    <td>${checkOut}</td>
                                    <td>${attendance.joint_purpose_details || 'N/A'}</td>
                                    <td>${duration}</td>
                                    <td>${attendance.check_in_address || 'N/A'}</td>
                                </tr>`;
                                        modalBodyTable.insertAdjacentHTML('beforeend', row);
                                    });
                                }

                                // Show the modal
                                const modalInstance = new bootstrap.Modal(attendanceModal);
                                modalInstance.show();
                            })
                            .catch(error => {
                                modalBodyTable.innerHTML =
                                    `<tr><td colspan="7" class="text-center">Failed to fetch data. Try again.</td></tr>`;
                                console.error('Error fetching attendance data:', error);
                            });
                    });
                });
            });
        </script>

        <script type="text/javascript">
            // $(document).ready(function() {
            // 	let fromInputs = document.querySelectorAll("[id^='from_']");
            //     let toInputs = document.querySelectorAll("[id^='to_']");
            //     let kmAsPerUserInputs = document.querySelectorAll("[id^='km_as_per_user_']");
            //     let kmAsPerGoogleInputs = document.querySelectorAll("[id^='km_as_per_google_map_']");

            //     let mode_expenseInputs = document.querySelectorAll("[id^='mode_expense_']");
            //     let fare_amountInputs = document.querySelectorAll("[id^='fare_amount_']");
            //     let da_totalInputs = document.querySelectorAll("[id^='da_total_']");
            //     let postageInputs = document.querySelectorAll("[id^='postage_']");
            //     let mobile_internetInputs = document.querySelectorAll("[id^='mobile_internet_']");
            //     let other_expenseInputs = document.querySelectorAll("[id^='other_expense_']");
            //     let other_expenses_amountInputs = document.querySelectorAll("[id^='other_expenses_amount_']");
            //     let print_stationeryInputs = document.querySelectorAll("[id^='print_stationery_']");

            // }

            $(document).ready(function() {
                $(document).on('change',
                    "[id^='mode_expense_'], [id^='calculates_fare_'], [id^='km_as_per_user_'], [id^='km_as_per_google_map_']",
                    function() {
                        let key = $(this).attr('id').split('_').pop(); // Extract the dynamic key from the ID

                        let modeExpense = $("#mode_expense_" + key).val();
                        let calculatesFare = $("#calculates_fare_" + key).val();
                        let kmAsPerUser = parseFloat($("#km_as_per_user_" + key).val()) || 0;
                        let kmAsPerGoogle = parseFloat($("#km_as_per_google_map_" + key).val()) || 0;

                        // Convert charges to float (handle empty or invalid values)
                        let twoWheelersCharges = {{ $policySetting->two_wheelers_charges ?? 0 }};
                        let fourWheelersCharges = {{ $policySetting->four_wheelers_charges ?? 0 }};

                        // console.log(fourWheelersCharges);

                        let fareAmountInput = $("#fare_amount_" + key);

                        if (modeExpense == '6') {
                            if (calculatesFare == 'by user') {
                                let fareAmount = kmAsPerUser * twoWheelersCharges;
                                fareAmountInput.val(fareAmount.toFixed(2)).prop('readonly', true);
                            } else if (calculatesFare == 'by google map') {
                                let fareAmount = kmAsPerGoogle * twoWheelersCharges;
                                fareAmountInput.val(fareAmount.toFixed(2)).prop('readonly', true);
                            } else {
                                // fareAmountInput.val('').prop('readonly', false);
                            }
                        } else if (modeExpense == '5') {
                            if (calculatesFare == 'by user') {
                                let fareAmount = kmAsPerUser * fourWheelersCharges;
                                fareAmountInput.val(fareAmount.toFixed(2)).prop('readonly', true);
                            } else if (calculatesFare == 'by google map') {
                                let fareAmount = kmAsPerGoogle * fourWheelersCharges;
                                fareAmountInput.val(fareAmount.toFixed(2)).prop('readonly', true);
                            } else {
                                // fareAmountInput.val('').prop('readonly', false);
                            }
                        } else {
                            // fareAmountInput.val('').prop('readonly', false);
                        }
                    });

                // Optional: Trigger calculation on page load if needed
                $("[id^='mode_expense_'], [id^='calculates_fare_'], [id^='km_as_per_user_'], [id^='km_as_per_google_map_']")
                    .each(function() {
                        $(this).trigger('change');
                    });
            });
        </script>

        <script type="text/javascript">
            function initAutocomplete() {
                // Select all "from" and "to" inputs dynamically based on the generated IDs
                let fromInputs = document.querySelectorAll("[id^='from_']");
                let toInputs = document.querySelectorAll("[id^='to_']");

                let kmAsPerGoogleInputs = document.querySelectorAll("[id^='km_as_per_google_map_']");

                let options = {
                    types: ["geocode"],
                    fields: ["name"],
                    componentRestrictions: {
                        country: "IN"
                    } // Restrict to India
                };

                function preventTyping(event) {
                    event.preventDefault();
                }

                // Remove "India" from place names in the "To" input
                function formatLocationName(locationName) {
                    return locationName.replace(/,?\s*India$/, '').trim();
                }

                // Initialize autocomplete for each "From" input dynamically
                fromInputs.forEach(fromInput => {
                    let fromAutocomplete = new google.maps.places.Autocomplete(fromInput, options);

                    // Ensure "From" input is formatted when the user selects a location
                    fromAutocomplete.addListener("place_changed", function() {
                        let place = fromAutocomplete.getPlace();
                        if (!place.name) return;

                        // Format the "From" location by removing "India"
                        let placeName = formatLocationName(place.name);

                        // Set the formatted location in the "From" input
                        fromInput.value = placeName;
                    });
                });

                // Initialize autocomplete for each "To" input dynamically
                toInputs.forEach(toInput => {
                    let toAutocomplete = new google.maps.places.Autocomplete(toInput, options);

                    // Ensure "To" input is formatted when the user selects a location
                    toAutocomplete.addListener("place_changed", function() {
                        let place = toAutocomplete.getPlace();
                        if (!place.name) return;

                        // Format the "To" location by removing "India"
                        let placeName = formatLocationName(place.name);

                        // Set the formatted location in the "To" input
                        toInput.value = `"${placeName}"`;
                    });
                });

                // You can also handle kmAsPerGoogleInputs similarly if needed
                kmAsPerGoogleInputs.forEach(kmInput => {
                    // Add event listeners or autocomplete functionality as needed for km inputs
                });

            }

            window.onload = initAutocomplete;
        </script>
        <script type="text/javascript">
            async function fetchDistanceMatrixOnClick(fromInput, toInput, kmInput) {
                // Fetch the values from the dynamically passed inputs
                const from = fromInput.value.trim();
                const to = toInput.value.trim();

                if (!from || !to) {
                    alert('Please enter both "From" and "To" locations.');
                    return;
                }

                // Parse "To" input which may have locations wrapped in double quotes
                const toLocations = parseLocations(to);
                console.log("to input", toLocations);

                // Start the journey from the "From" location
                const locations = [from, ...toLocations];

                let totalDistance = 0;

                try {
                    console.log('Sending request to google server...');
                    for (let i = 0; i < locations.length - 1; i++) {
                        const fromLocation = locations[i];
                        const toLocation = locations[i + 1];

                        // Fetch the distance between each consecutive pair of locations
                        const response = await fetch('/get-distance', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                            },
                            body: JSON.stringify({
                                from: fromLocation,
                                to: toLocation
                            }),
                        });

                        const data = await response.json();
                        console.log('Server response:', data);

                        if (response.ok) {
                            const distance = data.rows?.[0]?.elements?.[0]?.distance;

                            if (distance && distance.value) {
                                totalDistance += distance.value; // Add distance in meters
                            } else {
                                console.log(`Unable to retrieve distance from ${fromLocation} to ${toLocation}.`);
                                kmInput.value = '0';
                                return;
                            }
                        } else {
                            alert('Error fetching data: ' + (data.error || 'Unknown Error'));
                            kmInput.value = '0';
                            return;
                        }
                    }

                    // Convert total distance from meters to kilometers
                    const totalDistanceInKm = (totalDistance / 1000).toFixed(2);

                    // Fill the total distance in the km_as_per_google_map input field
                    kmInput.value = totalDistanceInKm;

                } catch (error) {
                    console.error('Error fetching distance matrix:', error);
                    kmInput.value = '0';
                    alert('An error occurred while fetching the distance.');
                }
            }

            // Function to parse the locations (handles both single and multiple locations with or without quotes)
            function parseLocations(input) {
                const regex = /"([^"]+)"|([^",]+)(?=\s*,|\s*$)/g;

                let matches, locations = [];

                while ((matches = regex.exec(input)) !== null) {
                    if (matches[1]) {
                        locations.push(matches[1]);
                    } else if (matches[2]) {
                        locations.push(matches[2]);
                    }
                }

                return locations.map(location => location.trim());
            }
        </script>

        <script type="text/javascript">
            // Dynamically attach event listeners to each set of input fields
            document.addEventListener('DOMContentLoaded', function() {
                const fromInputs = document.querySelectorAll("[id^='from_']");
                const toInputs = document.querySelectorAll("[id^='to_']");
                const kmInputs = document.querySelectorAll("[id^='km_as_per_google_map_']");

                fromInputs.forEach((fromInput, index) => {
                    const toInput = toInputs[index];
                    const kmInput = kmInputs[index];

                    const checkAndTriggerDistanceCalculation = () => {
                        if (fromInput.value.trim() !== "" && toInput.value.trim() !== "") {
                            console.log(
                                `Both "From" and "To" fields are filled for set ${index + 1}. Calculating distance...`
                            );
                            setTimeout(() => {
                                console.log('Calculating distance now...');
                                fetchDistanceMatrixOnClick(fromInput, toInput, kmInput);
                            }, 2000); // 2000 milliseconds = 2 seconds
                        }
                    };

                    // Attach input and blur event listeners
                    fromInput.addEventListener('input', checkAndTriggerDistanceCalculation);
                    toInput.addEventListener('input', checkAndTriggerDistanceCalculation);
                    toInput.addEventListener('blur', checkAndTriggerDistanceCalculation);
                });
            });
        </script>


        <!-- <script>
            async function fetchDistanceMatrixOnClick(event) {
                const row = event.target.closest('tr'); // Get the closest table row
                const from = row.querySelector('[id^="from_"]').value.trim(); // Get the 'from' field dynamically
                const to = row.querySelector('[id^="to_"]').value.trim(); // Get the 'to' field dynamically

                if (!from || !to) {
                    alert('Please enter both "From" and "To" locations.');
                    return;
                }

                try {
                    console.log('Sending request to server...');
                    const response = await fetch('/get-distance', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                        },
                        body: JSON.stringify({
                            from,
                            to
                        }),
                    });

                    const data = await response.json();
                    console.log('Server response:', data);

                    if (response.ok) {
                        const distance = data.rows?.[0]?.elements?.[0]?.distance;

                        if (distance && distance.value) {
                            row.querySelector('[id^="km_as_per_google_map_"]').value = (distance.value / 1000).toFixed(
                                2); // Set the distance in the km field
                        } else {
                            alert('Unable to retrieve distance. Try changing location name or fill valid location');
                        }
                    } else {
                        alert('Error fetching data: ' + (data.error || 'Unknown Error'));
                    }
                } catch (error) {
                    console.error('Error fetching distance matrix:', error);
                    alert('An error occurred while fetching the distance.');
                }
            }

            // Add event listeners to the km_as_per_google_map input fields dynamically
            document.querySelectorAll('[id^="km_as_per_google_map_"]').forEach(input => {
                input.addEventListener('click', fetchDistanceMatrixOnClick);
            });
        </script> -->

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                function calculateTotal(selector, totalFieldId) {
                    let totalAmount = 0;

                    // Select inputs correctly, even if they don't have an underscore
                    let inputs = document.querySelectorAll(`input[id^='${selector}']`);
                    inputs.forEach(input => {
                        totalAmount += parseFloat(input.value) || 0;
                    });

                    document.getElementById(totalFieldId).textContent = `₹ ${totalAmount.toFixed(2)}`;
                    return totalAmount;
                }

                function calculateGrandTotal() {
                    let totalFare = calculateTotal('fare_amount', 'totalFare');
                    let totalWorking = calculateTotal('da_total', 'totalWorking');
                    // let totalNotWorking = calculateTotal('expense_type_master_id', 'totalNotWorking');
                    let totalPostage = calculateTotal('postage', 'totalPostage');
                    let totalTelTGM = calculateTotal('mobile_internet', 'totaltel_tgm');
                    let totalAmount = calculateTotal('other_expenses_amount', 'totalAmount');
                    let totalStationery = calculateTotal('print_stationery', 'totalStationery'); // ✅ Fixing this

                    let grandTotal = totalFare + totalWorking + totalPostage + totalTelTGM + totalAmount +
                        totalStationery;

                    // Convert grand total to INR format
                    document.getElementById("grand_total").textContent = `₹ ${grandTotal.toFixed(2)}`;

                    // Convert number to words
                    document.getElementById("grand_total_in_word").textContent = numberToWords(grandTotal);
                }

                // Convert Number to Words Function
                function numberToWords(number) {
                    const a = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten",
                        "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
                        "Nineteen"
                    ];
                    const b = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

                    function convert(num) {
                        if (num < 20) return a[num];
                        if (num < 100) return b[Math.floor(num / 10)] + (num % 10 !== 0 ? " " + a[num % 10] : "");
                        if (num < 1000) return a[Math.floor(num / 100)] + " Hundred" + (num % 100 !== 0 ? " and " +
                            convert(num % 100) : "");
                        if (num < 100000) return convert(Math.floor(num / 1000)) + " Thousand" + (num % 1000 !== 0 ?
                            " " + convert(num % 1000) : "");
                        if (num < 10000000) return convert(Math.floor(num / 100000)) + " Lakh" + (num % 100000 !== 0 ?
                            " " + convert(num % 100000) : "");
                        return convert(Math.floor(num / 10000000)) + " Crore" + (num % 10000000 !== 0 ? " " + convert(
                            num % 10000000) : "");
                    }

                    return number === 0 ? "Zero" : convert(Math.floor(number)) + " Rupees Only";
                }

                // Attach event listeners to all inputs to update totals dynamically
                document.querySelectorAll("input").forEach(input => {
                    input.addEventListener("input", calculateGrandTotal);
                });

                // Run initially
                calculateGrandTotal();
            });
        </script>
        <script>
            document.querySelectorAll('.no-negative').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value < 0) {
                        this.value = 0;
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const allowedDAValues = @json([$policySetting['location_da'], $policySetting['ex_location_da'], $policySetting['outstation_da']]).map(Number); // Ensure numeric comparison

                const daInputs = document.querySelectorAll('input[name^="monthly_expense"][name$="[da_total]"]');

                daInputs.forEach(input => {
                    // Store the old value when the user focuses in
                    input.addEventListener('focus', function() {
                        this.dataset.oldValue = this.value;
                    });

                    // Validate on change
                    input.addEventListener('change', function() {
                        const value = parseInt(this.value.trim());
                        const isValid = allowedDAValues.includes(value);

                        if (!isValid && this.value !== '') {
                            alert("Invalid value entered! Allowed values are: " + allowedDAValues.join(
                                ', '));
                            this.value = this.dataset.oldValue || ''; // revert to old value or blank
                            this.focus();
                        }
                    });
                });
            });
        </script>




</body>

</html>
