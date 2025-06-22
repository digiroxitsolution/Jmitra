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
	<title>Sales Data</title>
	

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
		 @can('sales-list')

	<!--*******************
			Main Content start
		*******************-->
		<div class="container-fluid px-4">
		    <div class="row g-3 my-2">
				<div class="d-grid d-flex justify-content-end">
					<!-- <button class="btn add me-3 shadow" type="button">Merge</button> -->
			<a href="{{ route('sales_master.index') }}">
                <button class="btn btn-success me-3 shadow" type="button">Back</button>
            </a>

					
				</div>
			</div>
			
			@include('includes.message')

			
			<div class="row my-5">
				<div class="col">
					<div class="border table-header p-4 position-relative rounded-top-4">
							<h5 class="text-white">Sales Data</h5>
					</div>

					


					<div class="table-responsive">


					    <table  class="table border table-bordered bg-white rounded shadow-sm table-hover w-100">
					        <thead>
					            <tr>
					                
					                <th style="width: 10%;">S.No.</th>
					                <th style="width: 20%;">File Name</th>
					                <th style="width: 20%;">State</th>
					                <th style="width: 20%;">Amount</th>
					                <th style="width: 20%;">Date Of Sales</th>
					                
					            </tr>
					        </thead>
					        <tbody>
					            @php $i = 0; @endphp
					            @foreach ($salesData as $key => $sale)
					            <tr>
					                
					                <td>{{ ++$i }}</td>
					                <td class="text-truncate" style="max-width: 200px;">{{ $sale->salesMaster->file_name }}</td>
					                <td>{{ $sale->state->name }}</td>
					                <td>{{ $sale->sales_amount }}</td>
					                <td>{{ $sale->date_of_sales }}</td>
					                
					            </tr>
					            @endforeach
					             
					        </tbody>
					    </table>
					</div>

				</div>

			
		 		

		 		


			</div>
		</div>
		<!--*******************
			Main Content End
		 *****************-->
		


@else

@include('forbidden.forbidden')

@endcan

		 </div>
		
		
	</div>

	<!-- Bootstrap JS -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- Normal JS -->
	<script src="{{ asset('assets/js/script.js') }}"></script>

	<!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables JS -->
    


    

   
		
</body>
</html>

