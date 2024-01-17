@extends('layouts.app')

@section('title', ' - Edit User')

@section('content')
    <section class="container-fluid py-4">

        {{-- Header --}}
        <header class="mb-3">
            <div class="d-flex align-items-center gap-3">
                {{-- Back --}}
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                    <i class="fa-solid fa-chevron-left fa-xl"></i>
                </a>
                {{-- Title --}}
                <h2 class="mb-0">Edit User</h2>
            </div>
        </header>

        <hr class="mb-4">

        {{-- Form --}}
        @include('includes.user.form')
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/validations/user-form.js'])
@endsection
