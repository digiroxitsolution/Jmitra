 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fontawsome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Normal CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <!-- Favicon Icon -->
    <link rel="icon" href="{{ asset('assets/images/Favicon.png')}}">
    <title>Jmitra & Co. Pvt. Ltd</title>
</head>
<body id="loginBody">
    <div class="container" id="wrapper">
        
        <div class="d-flex justify-content-center align-items-center loginContainer">
            <div>
                <img src="{{ asset('assets/images/login.png')}}" id="loginImage">
            </div>
            <div>
                <div class="card shadow p-5">
                    <div class="card-head">
                        <img src="assets/images/Logo.png">
                    </div>
                    @include('includes.login_message')
                    <div class="card-body">
                        <h3 class="my-4 text-center text-decoration-underline login-title">Login</h3>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                              <label for="email" class="form-label">Email address</label>
                              <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" required autofocus autocomplete="username">
                            </div>
                            <div class="mb-3">
                              <label for="password" class="form-label">Password</label>
                              <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                            </div>
                            <a href="dashboard.html" class="text-decoration-none">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Login</button>
                            </div>
                            </a>
                     </form>
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Normal JS -->
    <script src="{{ asset('assets/js/script.js')}}"></script>
</body>
</html>