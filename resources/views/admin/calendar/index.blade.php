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

            {{-- Filters --}}
            <form class="d-flex justify-content-end gap-2 py-4">


                {{-- Services --}}
                <div id="filter_services" class="dropdown checkbox-list">

                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filter_services_toggler"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                        Services
                    </button>

                    <ul class="dropdown-menu text-light" aria-labelledby="filter_services_toggler">
                        @foreach ($services as $service)
                            <li>
                                <input class="form-check-input" type="checkbox"
                                    @if (in_array($service->id, $filter_services)) checked @endif id="filter_service-{{ $service->id }}"
                                    value="{{ $service->id }}" name="filter_services[]">
                                <label class="form-check-label"
                                    for="filter_service-{{ $service->id }}">{{ $service->name }}</label>
                            </li>
                        @endforeach
                    </ul>

                </div>

                {{-- Actions --}}
                <div>
                    <button class="btn btn-success">
                        <i class="fas fa-rotate"></i>
                    </button>
                </div>
            </form>

            {{-- Calendar --}}
            <div id="calendar" data-events='@json($events)' data-opening-hours='@json($openingHours)'
                data-holidays='@json($closedDays)'></div>

        </div>
    </section>

    {{-- Modals --}}
    @include('includes.calendar.modal_form')

@endsection

@section('scripts')
    @vite(['resources/js/commons/calendar.js', 'resources/js/validations/appointment-form.js'])
@endsection
