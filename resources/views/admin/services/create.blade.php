@extends('layouts.app')

@section('title', ' - Add Service')

@section('content')
    <section class="container-fluid py-4">

        {{-- Header --}}
        <header class="mb-4">
            <div class="d-flex align-items-center gap-3">
                {{-- Back --}}
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-light">
                    <i class="fa-solid fa-chevron-left fa-xl"></i>
                </a>
                {{-- Title --}}
                <h2 class="mb-0">Add Service</h2>
            </div>
        </header>

        {{-- Form --}}
        @include('includes.service.form')

    </section>
@endsection

@section('scripts')
    @vite(['resources/js/validations/services-form'])
@endsection
