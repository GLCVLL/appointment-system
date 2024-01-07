@extends('layouts.app')

@section('content')
    <section class="container py-4">

        {{-- Header --}}
        <header class="mb-3">

            {{-- Title --}}
            <div class="d-flex align-items-center gap-3">

                {{-- Back --}}
                <a href="{{ route('admin.opening-hours.index') }}" class="btn btn-light">
                    <i class="fa-solid fa-chevron-left fa-xl"></i>
                </a>

                {{-- Title --}}
                <h2 class="fw-bold display-6 mb-0">Edit Opening Hours</h2>

            </div>

        </header>

        <hr class="mb-4">
        {{-- Form --}}
        @include('includes.opening-hours.form')

    </section>
@endsection
