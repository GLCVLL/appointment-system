@extends('layouts.app')

@section('title', ' - ' . __('common.calendar'))

@section('content')
<section class="py-4 px-md-4">

    <div class="container-fluid">

        {{-- Header --}}
        <header class="mb-4">

            {{-- Title --}}
            <h2 class="mb-0">{{ __('common.calendar') }}</h2>
        </header>

        {{-- Filters --}}
        <div class="d-flex justify-content-end gap-2 py-4">


            {{-- Services --}}
            <div id="filter_services" class="dropdown checkbox-list">

                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filter_services_toggler"
                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    {{ __('common.services') }}
                </button>

                <ul class="dropdown-menu text-light" aria-labelledby="filter_services_toggler">
                    @foreach ($services as $service)
                    <li>
                        <input class="form-check-input" type="checkbox" data-name="{{ $service->name }}"
                            id="filter_service-{{ $service->id }}" value="{{ $service->id }}">
                        <label class="form-check-label"
                            for="filter_service-{{ $service->id }}">{{ $service->name }}</label>
                    </li>
                    @endforeach
                </ul>

            </div>

        </div>

        {{-- Calendar --}}
        <div id="calendar" data-events='@json($events)' data-opening-hours='@json($openingHours)'
            data-holidays='@json($closedDays)' data-services='@json($services->map->only(["id", "duration" ])->values())'
            data-booking-interval='@json($bookingInterval)'></div>

    </div>
</section>

{{-- Modals --}}
@include('includes.calendar.modal_form')

@endsection

@section('scripts')
@vite(['resources/js/commons/calendar.js', 'resources/js/validations/appointment-form.js'])
@endsection