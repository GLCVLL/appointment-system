<div class="app-sidebar">
    <nav>

        {{-- Logo --}}
        <a href="{{ route('admin.home') }}" class="app-sidebar-logo">
            <img src="{{ asset('img/logoSmartphone.webp') }}" alt="logo-smartPhone" class="d-md-none img-fluid">
            <img src="{{ asset('img/logoDesktop.webp') }}" alt="logo-desktop" class="d-none d-md-inline img-fluid">

        </a>

        {{-- Menu --}}
        <ul class="app-sidebar-menu">

            @guest

                {{-- Guest Home --}}
                <li>
                    <a href="{{ url('/') }}" class="@if (Route::is('guest.home')) active @endif">
                        <i class="fas fa-home fa-xl fa-fw"></i>
                        <span class="d-none d-md-inline">{{ __('common.home') }}</span>
                    </a>
                </li>
            @else
                {{-- Admin Home --}}
                <li>
                    <a href="{{ route('admin.home') }}" class="@if (Route::is('admin.home')) active @endif">
                        <i class="fas fa-home fa-xl fa-fw"></i>
                        <span class="d-none d-md-inline">{{ __('common.home') }}</span>
                    </a>
                </li>

                {{-- Calendar --}}
                <li>
                    <a href="{{ route('admin.calendar.index') }}" class="@if (Route::is('admin.calendar*')) active @endif">
                        <i class="fas fa-calendar-days fa-xl fa-fw"></i>
                        <span class="d-none d-md-inline">{{ __('common.calendar') }}</span>
                    </a>
                </li>

                {{-- Appointments --}}
                <li>
                    <a href="{{ route('admin.appointments.index') }}"
                        class="@if (Route::is('admin.appointments*')) active @endif">
                        <i class="fas fa-calendar-check fa-xl fa-fw"></i>
                        <span class="d-none d-md-inline">{{ __('common.appointments') }}</span>
                    </a>
                </li>

                {{-- Opening Hours --}}
                <li>
                    <a href="{{ route('admin.opening-hours.index') }}"
                        class="@if (Route::is('admin.opening-hours*')) active @endif">
                        <i class="fas fa-hourglass fa-xl fa-fw"></i>
                        <span class="d-none d-md-inline">{{ __('common.opening_hours') }}</span>
                    </a>
                </li>

                {{-- Closed Days --}}
                <li>
                    <a href="{{ route('admin.closed-days.index') }}"
                        class="@if (Route::is('admin.closed-days*')) active @endif">
                        <i class="fas fa-shop fa-xl fa-fw"></i>
                        <span class="d-none d-md-inline">{{ __('common.closed_days') }}</span>
                    </a>
                </li>

                {{-- Users --}}
                <li>
                    <a href="{{ route('admin.users.index') }}" class="@if (Route::is('admin.users*')) active @endif">
                        <i class="fas fa-users fa-xl fa-fw"></i>
                        <span class="d-none d-md-inline">{{ __('common.clients') }}</span>
                    </a>
                </li>

                {{-- categories --}}
                <li>
                    <a href="{{ route('admin.categories.index') }}"
                        class="@if (Route::is('admin.categories*')) active @endif">
                        <i class="fas fa-boxes fa-xl fa-fw"></i>
                        <span class="d-none d-md-inline">{{ __('common.categories') }}</span>
                    </a>
                </li>

                {{-- services --}}
                <li>
                    <a href="{{ route('admin.services.index') }}" class="@if (Route::is('admin.services*')) active @endif">
                        <i class="fas fa-scissors fa-xl fa-fw"></i>
                        <span class="d-none d-md-inline">{{ __('common.services') }}</span>
                    </a>
                </li>


            @endguest
        </ul>

    </nav>
</div>
