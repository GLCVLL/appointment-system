@extends('layouts.app')

@section('title', ' - ' . __('opening_hours.edit'))

@section('content')
    <section class="p-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="mb-4">

                {{-- Title --}}
                <div class="d-flex align-items-center gap-3">

                    {{-- Back --}}
                    <a href="{{ route('admin.opening-hours.index') }}" class="btn btn-outline-light">
                        <i class="fa-solid fa-chevron-left fa-xl"></i>
                    </a>

                    {{-- Title --}}
                    <h2 class="mb-0">{{ __('opening_hours.edit') }}</h2>

                </div>

            </header>

            {{-- Form --}}
            @include('includes.opening-hours.form')

        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/validations/opening-hours-form.js'])
@endsection
