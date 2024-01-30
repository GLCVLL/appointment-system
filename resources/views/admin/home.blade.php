@extends('layouts.app')

@section('content')
    <section class="py-4 px-md-4">
        <div class="container-fluid">

            <header class="d-flex justify-content-between align-items-center mb-4">

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
                <div class="col">

                    {{-- New Clients Stats --}}
                    <div class="card p-2">

                        <h3 class="text-center">New Clients</h3>

                        <canvas id="chart-users" data-values='@json($clients_data)'
                            data-labels='@json($clients_labels)'></canvas>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/charts/chart.js'])
@endsection
