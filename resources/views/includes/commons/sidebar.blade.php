<div class="app-sidebar">
    <nav>

        {{-- Logo --}}
        <a href="{{ route('admin.home') }}" class="app-sidebar-logo">
            <h2 class="fw-bold mb-0">A</h2>
        </a>

        {{-- Menu --}}
        <ul class="app-sidebar-menu">

            @guest

                {{-- Guest Home --}}
                <li>
                    <a href="{{ url('/') }}" class="@if (Route::is('guest.home')) active @endif">
                        <i class="fas fa-home fa-xl"></i>
                    </a>
                </li>

                {{-- Login/Register --}}
                <li class="dropend">

                    <a id="login-menu" class="dropdown-toggle @if (Route::is('login') || Route::is('register')) active @endif" href="#"
                        role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user fa-xl"></i>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="login-menu">
                        <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                        <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                    </div>
                </li>
            @else
                {{-- Admin Home --}}
                <li>
                    <a href="{{ route('admin.home') }}" class="@if (Route::is('admin.home')) active @endif">
                        <i class="fas fa-home fa-xl"></i>
                    </a>
                </li>

                {{-- Opening Hours --}}
                <li>
                    <a href="{{ route('admin.opening-hours.index') }}"
                        class="@if (Route::is('admin.opening-hours*')) active @endif">
                        <i class="fas fa-hourglass fa-xl"></i>
                    </a>
                </li>

                {{-- categories --}}
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="@if (Route::is('admin.categories*')) active @endif">
                        <i class="fas fa-boxes fa-xl"></i>
                    </a>
                </li>

                {{-- services --}}
                <li>
                    <a href="{{ route('admin.services.index') }}" class="@if (Route::is('admin.categories*')) active @endif">
                        <i class="fas fa-scissors fa-xl"></i>
                    </a>
                </li>

                {{-- Profile --}}
                <li class="dropend">

                    <a id="profile-menu" class="dropdown-toggle @if (Route::is('profile*')) active @endif"
                        href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user fa-xl"></i>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="profile-menu">
                        <a class="dropdown-item" href="{{ url('profile') }}">{{ __('Profile') }}</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>

    </nav>
</div>
