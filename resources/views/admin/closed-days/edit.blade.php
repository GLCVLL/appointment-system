@extends('layouts.app')

@section('title', ' - Edit Closed Days')

@section('content')
    <section class="container-fluid py-4">

        {{-- Header --}}
        <header class="mb-4">

            {{-- Title --}}
            <div class="d-flex align-items-center gap-3">

                {{-- Back --}}
                <a href="{{ route('admin.closed-days.index') }}" class="btn btn-outline-light">
                    <i class="fa-solid fa-chevron-left fa-xl"></i>
                </a>

                {{-- Title --}}
                <h2 class="mb-0">Edit Closed Days</h2>

            </div>

        </header>

        {{-- Form --}}
        @include('includes.closed-days.form')

    </section>
@endsection

@section('scripts')
    @vite(['resources/js/validations/closed-days-form'])
@endsection
