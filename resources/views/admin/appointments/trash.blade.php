@extends('layouts.app')

@section('title', ' - ' . __('appointments.trash'))

@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="d-flex justify-content-between align-items-center mb-4">

                {{-- Title --}}
                <h2 class="mb-0">{{ __('appointments.trash') }}</h2>

                {{-- Back --}}
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-light">
                    <i class="fa-solid fa-chevron-left fa-xl"></i>
                </a>
            </header>

            {{-- List --}}
            <div class="table-responsive card p-3">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('appointments.client') }}</th>
                            <th scope="col">{{ __('appointments.service') }}</th>
                            <th scope="col">{{ __('appointments.date') }}</th>
                            <th scope="col">{{ __('appointments.start_time') }}</th>
                            <th scope="col">{{ __('appointments.end_time') }}</th>
                            <th scope="col">{{ __('appointments.notes') }}</th>

                            <th scope="col" class="text-center">{{ __('appointments.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->user->name }}</td>
                                <td>
                                    @forelse ($appointment->services as $service)
                                        {{ $service->name }} <br>
                                    @empty
                                        -
                                    @endforelse
                                </td>
                                <td>{{ $appointment->getDate('date', 'd/m/Y') }}</td>
                                <td>{{ $appointment->getDate('start_time', 'H:i') }}</td>
                                <td>{{ $appointment->getDate('end_time', 'H:i') }}</td>
                                <td>{{ $appointment->notes }}</td>

                                {{-- Actions --}}
                                <td>
                                    <div class="d-flex justify-content-end gap-2">

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.appointments.drop', $appointment) }}" method="POST"
                                            class="delete-form"
                                            data-modal-name="appointment of {{ $appointment->getDate('start_time', 'H:i') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-3">{{ __('appointments.no_appointments') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end align-items-center">
                    {{-- Delete All --}}
                    <form action="{{ route('admin.appointments.dropAll') }}" method="POST" class="delete-form"
                        data-modal-name='all appointments'>
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">
                            {{ __('common.delete') }}
                        </button>
                    </form>
                </div>
                {{-- PAGINATION --}}
                @if ($appointments->hasPages())
                    {{ $appointments->links() }}
                @endif
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/commons/modal-delete'])
@endsection
