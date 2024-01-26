@extends('layouts.app')
@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="mb-4">
                <h2 class="mb-0">Home</h2>
            </header>

            {{-- Content --}}
            <div class="card p-5 mb-4 rounded-3">
                <div class="container py-3">
                    <h3 class="display-5 fw-bold">
                        Welcome to Laravel+Bootstrap 5
                    </h3>

                    <p class="col-md-8 fs-4">This a preset package with Bootstrap 5 views for laravel projects including
                        laravel
                        breeze/blade. It works from laravel 9.x to the latest release 10.x</p>
                    <a href="https://packagist.org/packages/pacificdev/laravel_9_preset" class="btn btn-primary btn-lg"
                        type="button">Documentation</a>
                </div>
            </div>
        </div>
    </section>
@endsection
