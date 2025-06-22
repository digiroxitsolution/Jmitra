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
	<!-- <style type="text/css">
		.dataTables_wrapper .pagination {
		    margin: 0;
		}

		.pagination-container ul.pagination {
		    justify-content: center;
		}

		.pagination .page-link {
		    color: #007bff;
		    border: none;
		    background: #f8f9fa;
		    transition: background 0.3s, color 0.3s;
		}

		.pagination .page-link:hover {
		    background: #007bff;
		    color: #fff;
		}

		.pagination .active .page-link {
		    background: #007bff;
		    color: #fff;
		    border: none;
		}

		.dataTables_length label {
		    margin-right: 10px;
		    font-weight: 500;
		}

	</style> -->
	@yield('additional_style')

</head>
<body>
	<div class="d-flex" id="wrapper">
		@include('includes.sidebar')

		<!-- Page Content + Nav Bar -->
		<div id="page-content-wrapper">
		<!--*******************
			Navbar start
		*******************-->
		@include('includes.nav')
		<!--*******************
			Navbar end
		*******************-->
		 @yield('content')
		 </div>
		
		
	</div>

	<!-- Bootstrap JS -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- Normal JS -->
	<script src="{{ asset('assets/js/script.js') }}"></script>

	<!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap JS (Optional) -->
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    

    <script>
	    
	</script>

	@yield('additional_script')

		
</body>
</html>