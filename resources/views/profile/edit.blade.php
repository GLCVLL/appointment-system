@extends('layouts.app')
@section('content')
    <section class="p-4">

        <div class="container-fluid">
            <h2 class="mb-4">
                {{ __('Profile') }}
            </h2>
            <div class="card p-4 mb-4 shadow rounded-lg">

                @include('profile.partials.update-profile-information-form')

            </div>

            <div class="card p-4 mb-4 shadow rounded-lg">


                @include('profile.partials.update-password-form')

            </div>

            <div class="card p-4 mb-4 shadow rounded-lg">


                @include('profile.partials.delete-user-form')

            </div>
        </div>
    </section>
@endsection
