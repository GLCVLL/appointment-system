@extends('layouts.app')

@section('content')
    <section class="py-4 px-md-4">
        <div class="container-fluid">

            <header class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                {{-- Title --}}
                <h2 class="mb-0">
                    {{ __('Dashboard') }}
                </h2>

                {{-- Filters --}}
                <form class="row g-3 align-items-center">

                    <div class="col-auto">
                        <input type="date" id="date_min" class="form-control" name="date_min"
                            value="{{ $filters['date_min'] }}">
                    </div>

                    <div class="col-auto">
                        <input type="date" id="date_max" class="form-control" name="date_max"
                            value="{{ $filters['date_max'] }}">
                    </div>

                    <div class="col-auto">
                        <button class="btn btn-success"><i class="fas fa-rotate"></i></button>
                    </div>

                </form>

            </header>

            <div class="row">

                {{-- New Clients Stats --}}
                <div class="col-12 col-md-6 col-lg-4 mb-4">

                    <div class="card stat-card h-100 p-4">

                        {{-- Card Header --}}
                        <div class="d-flex justify-content-between align-items-center pb-2">

                            {{-- Title --}}
                            <h6>NEW CLIENTS</h6>

                            {{-- Dropdown Menu --}}
                            <div class="dropdown">

                                <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis"></i>
                                </button>

                                <ul class="dropdown-menu p-2">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                            <i class="fas fa-eye me-2"></i>
                                            View
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        {{-- Body  --}}
                        <div class="row">

                            <div class="col-6 col-lg-12 col-xl-6">
                                <div class="stat-card-value mb-2">{{ $clients_chart['tot'] }}</div>
                                <div class="text-{{ $clients_chart['increment'] >= 0 ? 'success' : 'danger' }}">
                                    {{ $clients_chart['increment'] }}%
                                    <i class="ms-1 fas fa-arrow-{{ $clients_chart['increment'] >= 0 ? 'up' : 'down' }}"></i>
                                </div>
                            </div>

                            <div class="col-6 col-lg-12 col-xl-6">
                                <canvas id="chart-users" class="w-100" data-values='@json($clients_chart['data'])'
                                    data-labels='@json($clients_chart['labels'])'></canvas>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Appointments Stats --}}
                <div class="col-12 col-md-6 col-lg-4 mb-4">

                    <div class="card stat-card h-100 p-4">

                        {{-- Card Header --}}
                        <div class="d-flex justify-content-between align-items-center pb-2">

                            {{-- Title --}}
                            <h6>APPOINTMENTS</h6>

                            {{-- Dropdown Menu --}}
                            <div class="dropdown">

                                <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis"></i>
                                </button>

                                <ul class="dropdown-menu p-2">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.appointments.index') }}">
                                            <i class="fas fa-eye me-2"></i>
                                            View
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        {{-- Body  --}}
                        <div class="row">

                            <div class="col-6 col-lg-12 col-xl-6">
                                <div class="stat-card-value mb-2">{{ $appointments_chart['tot'] }}</div>
                                <div class="text-{{ $appointments_chart['increment'] >= 0 ? 'success' : 'danger' }}">
                                    {{ $appointments_chart['increment'] }}%
                                    <i
                                        class="ms-1 fas fa-arrow-{{ $appointments_chart['increment'] >= 0 ? 'up' : 'down' }}"></i>
                                </div>
                            </div>

                            <div class="col-6 col-lg-12 col-xl-6">
                                <canvas id="chart-appointments" class="w-100" data-values='@json($appointments_chart['data'])'
                                    data-labels='@json($appointments_chart['labels'])'></canvas>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Profits Stats --}}
                <div class="col-12 col-md-6 col-lg-4 mb-4">

                    <div class="card stat-card h-100 p-4">

                        {{-- Card Header --}}
                        <div class="d-flex justify-content-between align-items-center pb-2">

                            {{-- Title --}}
                            <h6>PROFITS</h6>

                            {{-- Dropdown Menu --}}
                            <div class="dropdown">

                                <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis"></i>
                                </button>

                                <ul class="dropdown-menu p-2">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.services.index') }}">
                                            <i class="fas fa-eye me-2"></i>
                                            View
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        {{-- Body  --}}
                        <div class="row">

                            <div class="col-6 col-lg-12 col-xl-6">
                                <div class="stat-card-value mb-2">€{{ $profits_chart['tot'] }}</div>
                                <div class="text-{{ $profits_chart['increment'] >= 0 ? 'success' : 'danger' }}">
                                    {{ $profits_chart['increment'] }}%
                                    <i
                                        class="ms-1 fas fa-arrow-{{ $profits_chart['increment'] >= 0 ? 'up' : 'down' }}"></i>
                                </div>
                            </div>

                            <div class="col-6 col-lg-12 col-xl-6">
                                <canvas id="chart-profits" class="w-100" data-values='@json($profits_chart['data'])'
                                    data-labels='@json($profits_chart['labels'])'></canvas>
                            </div>

                        </div>

                    </div>
                </div>


                {{-- Profits Details --}}
                <div class="col-12 mb-4">
                    <div class="card stat-card h-100 p-4">

                        <h3 class="mb-3">Profits Details</h4>

                            <canvas id="chart-profits-big" class="w-100" data-values='@json($profits_chart['data'])'
                                data-labels='@json($profits_chart['labels'])'></canvas>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/charts/chart.js'])
@endsection
