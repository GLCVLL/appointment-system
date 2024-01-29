<header class="app-header">
    <nav class="h-100">

        {{-- Sidebar Toggrer --}}
        <button id="sidebar-toggler" class="btn app-header-toggler d-md-none">
            <i class="fas fa-bars fa-2xl"></i>
        </button>

        {{-- Menu --}}
        <ul class="app-header-menu">

            @guest
                {{-- Login/Register --}}
                <li class="dropdown">

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
                {{-- Notifications --}}
                <li>
                    <a href="#" class="position-relative">
                        <i class="far fa-bell fa-xl">
                            <span class="indicator"></span>
                        </i>
                    </a>
                </li>

                {{-- Profile --}}
                <li class="dropdown">

                    <a id="profile-menu" class="dropdown-toggle @if (Route::is('profile*')) active @endif"
                        href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="profile-icon">
                            {{ auth()->user()->name ? substr(auth()->user()->name, 0, 1) : 'U' }}
                        </span>
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
</header>
