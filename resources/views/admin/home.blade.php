@extends('layouts.app')

@section('content')
    <section class="py-4 px-md-4">
        <div class="container-fluid">
            <h2 class="mb-4">
                {{ __('Dashboard') }}
            </h2>
            <div class="row justify-content-center">
                <div class="col">
                    <div class="card p-2">
                        <div class="card-header">{{ __('User Dashboard') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            {{ __('You are logged in!') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
