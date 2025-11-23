@extends('layouts.app')

@section('title', ' - ' . __('categories.add'))

@section('content')
    <section class="p-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="mb-4">

                {{-- Title --}}
                <div class="d-flex align-items-center gap-3">

                    {{-- Back --}}
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light">
                        <i class="fa-solid fa-chevron-left fa-xl"></i>
                    </a>

                    {{-- Title --}}
                    <h2 class="mb-0">{{ __('categories.add') }}</h2>

                </div>

            </header>

            {{-- Form --}}
            @include('includes.category.form')

        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/validations/categories-form'])
@endsection
