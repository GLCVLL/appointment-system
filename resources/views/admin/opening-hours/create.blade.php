@extends('layouts.app')

@section('title', ' - Add Opening Hours')

@section('content')
    <section class="container-fluid py-4">

        {{-- Header --}}
        <header class="mb-4">

            {{-- Title --}}
            <div class="d-flex align-items-center gap-3">

                {{-- Back --}}
                <a href="{{ route('admin.opening-hours.index') }}" class="btn btn-outline-light">
                    <i class="fa-solid fa-chevron-left fa-xl"></i>
                </a>

                {{-- Title --}}
                <h2 class="mb-0">Add Opening Hours</h2>

            </div>

        </header>

        {{-- Form --}}
        @include('includes.opening-hours.form')

    </section>
@endsection

@section('scripts')
    @vite(['resources/js/validations/opening-hours-form'])
@endsection
