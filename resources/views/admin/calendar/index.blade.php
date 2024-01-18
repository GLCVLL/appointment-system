@extends('layouts.app')

@section('title', ' - Appointments')

@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="mb-4">

                {{-- Title --}}
                <h2 class="mb-0">Calendar</h2>
            </header>

            {{-- Calendar --}}
            <div id="calendar" data-events='@json($events)'></div>

        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/commons/calendar.js'])
@endsection
