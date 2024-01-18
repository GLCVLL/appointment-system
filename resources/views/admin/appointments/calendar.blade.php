@extends('layouts.app')

@section('title', ' - Appointments')

@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="d-flex justify-content-between align-items-center mb-4">

                {{-- Title --}}
                <h2 class="mb-0">Calendar</h2>

                {{-- Add Category --}}
                <a href="{{ route('admin.appointments.create') }}" class="btn btn-success btn-circle">
                    <i class="fas fa-plus fa-lg"></i>
                </a>
            </header>

            {{-- Calendar --}}
            <div id="calendar" data-events='@json($events)'></div>

        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/commons/calendar.js'])
@endsection
