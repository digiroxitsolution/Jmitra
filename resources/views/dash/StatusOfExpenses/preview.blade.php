<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="icon" href="{{ asset('assets/images/Favicon.png') }}">
    <title>@yield('title', config('app.name', 'Jmitra & Co. Pvt. Ltd'))</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em; /* Adjust font size for better fit */
        }
        th, td {
            border: 1px solid #dee2e6; /* Bootstrap table border color */
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #e9ecef; /* Light gray for headers */
            font-weight: bold;
            /* Ensure vertical borders for headers */
            border-right: 1px solid #c0c0c0; /* Slightly darker for header vertical lines */
        }
        th:last-child {
            border-right: 1px solid #dee2e6; /* Last header cell border matches other cells */
        }
        .table-active {
            background-color: #e2e6ea !important; /* Lighter active row */
        }
        .time-cell-separator {
            display: block;
            border-top: 1px solid #dee2e6;
            margin-top: 5px;
            padding-top: 5px;
        }
        /* Style for print: elements with 'exclude-from-pdf' class will be hidden */
        @media print {
            .exclude-from-pdf {
                display: none !important;
            }
        }
    </style>
</head>
<body>
   <div id="pdf-content">

    <div class="d-flex" id="wrapper">
        <div id="page-content-wrapper">
            <div class="row">
                <div class="card">
                    <div class="card-dialog card-dialog-centered card-fullscreen">

                        <div class="card-header" style="background-color:#077fad; color:white;">
                          <h4 class="" style="height: 50px;">Preview of {{ \Carbon\Carbon::parse($month)->format('F') }} Expenses
                            <button id="download-pdf" class="btn btn-primary exclude-from-pdf">Download PDF</button>
                          </h4>
                        </div>

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-lg-4 col-12">
                                    Company Name: @if (Auth::user()->userDetail && Auth::user()->userDetail->companyMaster->company_name )
                                        {{ Auth::user()->userDetail->companyMaster->company_name  }}
                                        @else
                                            N/A
                                        @endif
                                </div>
                                <div class="col-lg-4 col-12">
                                    Division: @if (Auth::user()->userDetail && Auth::user()->userDetail->DivisonMaster->name )
                                        {{ Auth::user()->userDetail->DivisonMaster->name  }}
                                        @else
                                            N/A
                                        @endif
                                </div>
                                <div class="col-lg-4 col-12">
                                    Month: {{ \Carbon\Carbon::parse($month)->format('F') }}
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 col-12">
                                    Name: @if (Auth::user()->userDetail && Auth::user()->name)
                                        {{ Auth::user()->name }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                                <div class="col-lg-4 col-12">
                                    Designation: @if (Auth::user()->userDetail && Auth::user()->userDetail->designation->name )
                                        {{ Auth::user()->userDetail->designation->name  }}
                                        @else
                                            N/A
                                        @endif
                                </div>
                                <div class="col-lg-4 col-12">
                                    Location: @if (Auth::user()->userDetail && Auth::user()->userDetail->locationMaster->working_location )
                                        {{ Auth::user()->userDetail->locationMaster->working_location  }}
                                        @else
                                            N/A
                                        @endif
                                </div>
                            </div>

                            <div class="row mb-4">
                                <table class="table table-bordered table-hover text-center table-light">
                                    <thead>
                                        <tr class="table-active fw-bold">
                                            <th>Date</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>DEP Time</th>
                                            <th>ARR Time</th>
                                            <th>KM as per User</th>
                                            <th>KM as per Google</th>
                                            <th>Mode</th>
                                            <th>Fare Amount</th>
                                            <th>DA Working</th>
                                            <th>DA N. Working</th>
                                            <th>Postage</th>
                                            <th>TEL/TGM</th>
                                            <th>Print Stationery</th>
                                            <th>Other Expenses Purpose</th>
                                            <th>Other Expenses Amount</th>
                                            <th class="exclude-from-pdf">Attendance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($statement_of_expense as $key => $monthly_expense)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($monthly_expense->expense_date)->format('d-m-Y') }}</td>
                                            <td>
                                                @if($monthly_expense->expense_type_master_id == 4 || $monthly_expense->expense_type_master_id == 5 || $monthly_expense->expense_type_master_id == 6 || $monthly_expense->expense_type_master_id == 9)
                                                    {{ $monthly_expense->ExpenseTypeMaster->expense_type  }}
                                                @else
                                                    {{ $monthly_expense->from }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($monthly_expense->expense_type_master_id == 4 || $monthly_expense->expense_type_master_id == 5 || $monthly_expense->expense_type_master_id == 6 || $monthly_expense->expense_type_master_id == 9)
                                                    {{ $monthly_expense->ExpenseTypeMaster->expense_type  }}
                                                @else
                                                    {{ $monthly_expense->to }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $monthly_expense->departure_time ? \Carbon\Carbon::parse($monthly_expense->departure_time)->format('d-m-Y') : '' }}
                                                <span class="time-cell-separator">{{ $monthly_expense->departure_time ? \Carbon\Carbon::parse($monthly_expense->departure_time)->format('h:i A') : '' }}</span>
                                            </td>
                                            <td>
                                                {{ $monthly_expense->arrival_time ? \Carbon\Carbon::parse($monthly_expense->arrival_time)->format('d-m-Y') : '' }}
                                                <span class="time-cell-separator">{{ $monthly_expense->arrival_time ? \Carbon\Carbon::parse($monthly_expense->arrival_time)->format('h:i A') : '' }}</span>
                                            </td>
                                            <td>{{ $monthly_expense->km_as_per_user ?? 'N/A' }}</td>
                                            <td>{{ $monthly_expense->km_as_per_google_map ?? 'N/A' }}</td>
                                            <td>{{ $monthly_expense->ModeofExpenseMaster->mode_expense  ?? 'N/A' }}</td>
                                            <td>{{ $monthly_expense->fare_amount ?? 'N/A' }}</td>
                                            <td>
                                                @if ($monthly_expense->expense_type_master_id != 8)
                                                    {{ $monthly_expense->da_total }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($monthly_expense->expense_type_master_id == 8)
                                                    {{ $monthly_expense->da_total }}
                                                @endif
                                            </td>
                                            <td>{{ $monthly_expense->postage ?? 'N/A' }}</td>
                                            <td></td> {{-- TEL/TGM column seems consistently empty in your provided data --}}
                                            <td>{{ $monthly_expense->print_stationery ?? 'N/A' }}</td>
                                            <td>{{ $monthly_expense->OtherExpenseMaster->other_expense ?? 'N/A' }}</td>
                                            <td>{{ $monthly_expense->other_expenses_amount ?? 'N/A' }}</td>
                                            <td class="table-active exclude-from-pdf">
                                                        <a href="javascript:void(0);"
                                                           data-url="{{ route('get_other_attendance', ['id' => $usser->id, 'attendance_date' => $monthly_expense->expense_date]) }}"
                                                           class="view-attendance-btn">
                                                            <button type="button" class="btn btn-info float-end ms-2 text-white">
                                                                View
                                                            </button>
                                                        </a>

                                                    </td>
                                        </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="8" class="table-active">Total</td>
                                            <td>Rs. {{ $total_fare_amount ?? 'N/A' }}</td>
                                            <td>Rs.{{ $total_da_location_working }} </td>
                                            <td>Rs. {{ $total_da_location_not_working }} </td>
                                            <td>Rs. {{ $total_postage ?? 'N/A' }}</td>
                                            <td>Rs. {{ $total_mobile_internet ?? 'N/A' }}</td>
                                            <td></td>
                                            <td>Rs. {{ $total_other_expense_amount ?? 'N/A' }}</td>
                                            <td>{{ $total_print_stationery }}</td>
                                            <td class="exclude-from-pdf">***</td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td class="table-active">RUPEES in Words</td>
                                            <td colspan="4">{{ $grand_total_in_words }}</td>
                                            <td class="table-active">Grand Total</td>
                                            <td colspan="3">Rs. {{ $grand_total }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mb-4">
                                <div class="col-lg-6 col-12 fw-bold">Signature of Field Staff: __________ __________ __________</div>
                                <div class="col-lg-6 col-12 fw-bold">Signature of Manager: __________ __________ __________</div>
                            </div>

                            <div class="row mb-4">
                                <table class="table table-bordered table-hover table-light mt-5">
                                    <tbody>
                                        <tr class="table-active fw-bold">
                                            <td>Total</td>
                                            <td colspan="2">Number</td>
                                        </tr>
                                        <tr>
                                            <td>Fare Amount</td>
                                            <td>{{ $total_fare_amount ?? '0' }}</td>
                                        </tr>
                                        <tr>
                                            <td>DA (Location) + DA (Ex-Location)</td>
                                            <td>{{ $total_Da ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>DA Outstation</td>
                                            <td>{{ $da_outstation ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Postage</td>
                                            <td>{{ $total_postage ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Mobile/ Internet</td>
                                            <td>{{ $total_mobile_internet ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Print_Stationery</td>
                                            <td>{{ $total_print_stationery ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Other Expenses Amount</td>
                                            <td>{{ $total_other_expense_amount ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-primary">Grand Total</td>
                                            <td colspan="2"><input type="text" class="form-control" value="{{ $grand_total }}" disabled></td>
                                        </tr>
                                        <tr>
                                            <td class="text-primary">Advance Taken (If Any):</td>
                                            <td colspan="2">
                                                <input type="number" class="form-control" value="{{ $UserExpenseOtherRecords_filter->advance_taken }}" name="advance_taken" id="advance_taken" {{ $UserExpenseOtherRecords_filter->is_submitted === 1 ? 'disabled' : '' }} >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-primary">Remark of Advance Taken</td>
                                            <td colspan="2">
                                                <input type="text" class="form-control" value="{{ $UserExpenseOtherRecords_filter->remark_of_advance_taken }}" id="remark_of_advance_taken" name="remark_of_advance_taken" {{ $UserExpenseOtherRecords_filter->is_submitted === 1 ? 'disabled' : '' }} >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-primary">Balance Due to CO./EMP:</td>
                                            <td colspan="2"><input type="text" class="form-control" value="{{ $balance_dues }}" disabled></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mb-4">
                                <div class="form-check">
                                    <input class="form-check-input me-2 ms-1" type="checkbox" value="1" id="accept_policy" name="accept_policy" {{ $UserExpenseOtherRecords_filter->accept_policy == 1 ? 'checked' : '' }} {{ $UserExpenseOtherRecords_filter->is_submitted === 1 ? 'disabled' : '' }} >
                                    <label class="form-check-label text-muted" for="flexCheckIndeterminateAgree">
                                      I hereby confirmed that I verified the Expenses and found OK as per Travel/ Daily Allowance Policy.
                                    </label>
                                  </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-lg-4 col-12">Submitted by<br>@if (Auth::user()->userDetail && Auth::user()->name)
                                        {{ Auth::user()->name }}
                                    @else
                                        N/A
                                    @endif<br>
                                    {{ $UserExpenseOtherRecords_filter->date_of_submission ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->date_of_submission)->format('d-m-Y') : 'N/A' }}
                                    <br>
                                    {{ $UserExpenseOtherRecords_filter->date_of_submission ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->date_of_submission)->format('h:i A') : 'N/A' }}
                                </div>
                                <div class="col-lg-4 col-12">Verified by<br>{{ $UserExpenseOtherRecords_filter->verifiedBy?->name ?? 'N/A' }}<br>
                                    {{ $UserExpenseOtherRecords_filter->verified_time ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->verified_time)->format('d-m-Y') : 'N/A' }}
                                    <br>
                                    {{ $UserExpenseOtherRecords_filter->verified_time ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->verified_time)->format('h:i A') : 'N/A' }}
                                </div>
                                <div class="col-lg-4 col-12">Approved by<br>
                                    {{ $UserExpenseOtherRecords_filter->approvedBy?->name ?? 'N/A' }}<br>
                                    {{ $UserExpenseOtherRecords_filter->approved_time ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->approved_time)->format('d-m-Y') : 'N/A' }}
                                    <br>
                                    {{ $UserExpenseOtherRecords_filter->approved_time ? \Carbon\Carbon::parse($UserExpenseOtherRecords_filter->approved_time)->format('h:i A') : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="card-footer" style="height: 50px;">
                            {{-- Re-added the "Closed" button and marked it to be excluded from PDF --}}
                            <a href="{{ route('status_of_expenses')}}">
                                <button type="button" class="btn btn-danger float-end ms-2 exclude-from-pdf" data-bs-dismiss="modal">Closed</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dash.HODexpensePendingForVerification.viewAttendance')

<script type="text/javascript" src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const attendanceModal = document.getElementById('viewOfAttendenceModal');
        const modalBodyTable = attendanceModal.querySelector('tbody');

        document.querySelectorAll('.view-attendance-btn').forEach(button => {
            button.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                modalBodyTable.innerHTML = ''; // Clear the modal content

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            modalBodyTable.innerHTML = `<tr><td colspan="7" class="text-center">${data.error}</td></tr>`;
                        } else {
                            data.forEach(attendance => {
                                const formatDateTime = (dateTimeString) => {
                                    if (!dateTimeString) return 'N/A';
                                    const date = new Date(dateTimeString);
                                    if (isNaN(date.getTime())) return 'Invalid Date';
                                    const day = String(date.getDate()).padStart(2, '0');
                                    const month = String(date.getMonth() + 1).padStart(2, '0');
                                    const year = date.getFullYear();
                                    let hours = date.getHours();
                                    const minutes = String(date.getMinutes()).padStart(2, '0');
                                    const amPm = hours >= 12 ? 'PM' : 'AM';
                                    hours = hours % 12 || 12;
                                    return `${day}-${month}-${year} ${hours}:${minutes} ${amPm}`;
                                };

                                const checkIn = formatDateTime(attendance.check_in);
                                const checkOut = formatDateTime(attendance.check_out);

                                let duration = 'N/A';
                                if (checkIn !== 'N/A' && checkOut !== 'N/A') {
                                    const checkInDate = new Date(attendance.check_in);
                                    const checkOutDate = new Date(attendance.check_out);
                                    const timeDifference = checkOutDate - checkInDate;
                                    if (timeDifference > 0) {
                                        const hours = Math.floor(timeDifference / (1000 * 60 * 60));
                                        const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                                        duration = `${hours} hr ${minutes} min`;
                                    } else {
                                        duration = 'Invalid duration';
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
                        const modalInstance = new bootstrap.Modal(attendanceModal);
                        modalInstance.show();
                    })
                    .catch(error => {
                        modalBodyTable.innerHTML = `<tr><td colspan="7" class="text-center">Failed to fetch data. Try again.</td></tr>`;
                        console.error('Error fetching attendance data:', error);
                    });
            });
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById("download-pdf").addEventListener("click", function () {
        const element = document.getElementById("pdf-content");

        // Temporarily hide elements with the 'exclude-from-pdf' class
        document.querySelectorAll('.exclude-from-pdf').forEach(el => {
            el.style.display = 'none';
        });

        const opt = {
            margin: 0.5,
            filename: 'Expense_Report_A3_Landscape.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: {
                scale: 2,
                useCORS: true
            },
            jsPDF: {
                unit: 'mm',
                format: 'a3',
                orientation: 'landscape'
            },
            pagebreak: {
                mode: ['avoid-all', 'css', 'legacy']
            }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            // Restore visibility of elements after PDF is generated
            document.querySelectorAll('.exclude-from-pdf').forEach(el => {
                el.style.display = ''; // Revert to default display
            });
        });
    });
</script>
</body>
</html>