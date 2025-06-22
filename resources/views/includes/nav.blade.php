<nav class="navbar navbar-expand-lg navbar-light bg-white py-4 px-4 mx-lg-3 shadow-sm">
			<div class="d-flex align-items-center">
				<i class="fa-solid fa-align-left fs-4 me-3 primary-text" id="menu-toggle"></i>
				<h2 class="fs-2 m-0">{{ $title ?? 'Dashboard' }}</h2>
			</div>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-5">
					<!-- <li class="nav-item me-3">
						<i class="fa-solid fa-bell fs-4 mt-2" id="notifications"></i>
					</li> -->
					<li class="nav-item dropdown">
						<img src="{{ asset('assets/images/man.png')}}" alt="User" class="m-0 p-0 nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
							@if(Auth::check())
								<li class="text-start ps-3 fs-5"><span>{{ Auth::user()->name }}!</span></li>
							    
							@else
								<li class="text-start ps-3 fs-5"><span>Welcome, Guest!</span></li>
							    
							@endif
							
							<li>
							    <a href="{{ route('profile.edit') }}" class="dropdown-item">
							        <i class="fa-solid fa-user me-2"></i> Profile
							    </a>
							</li>

							

							<!-- <li><a href="#" class="dropdown-item"><i class="fa-solid fa-gear me-2"></i> Settings</a></li>
							<li> -->
								

								<a class="dropdown-item" href="{{ __('Logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa-solid fa-right-from-bracket me-2"></i>{{ __('Logout') }}</a>
				                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
				                    @csrf
				                </form>

							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>