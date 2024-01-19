@extends('layouts.app')

@section('title', ' - Appointments')

@section('content')
    <section class="p-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="d-flex justify-content-between align-items-center mb-4">

                {{-- Title --}}
                <h2 class="mb-0">Appointments</h2>

                {{-- Add Appointments --}}
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.appointments.create') }}" class="btn btn-success btn-circle">
                        <i class="fas fa-plus fa-lg"></i>
                    </a>
                    <small><a href="{{ route('admin.appointments.trash') }}" class="btn btn-outline-light">Trash</a></small>
                </div>
            </header>

            <div class="my-3">
                {{-- Date Filter Form --}}
                <form action="{{ route('admin.appointments.index') }}" method="GET">
                    <div class="row">
                        <div class="col-12 col-lg-3 col-sm-4 mb-2">
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-12 col-lg-3 col-sm-4 mb-2">
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-12 col-sm-4 col-lg-3">
                            <button type="submit" class="btn btn-success me-1">
                                <i class="fas fa-rotate"></i>
                            </button>
                            <a href="{{ route('admin.appointments.index') }}" class="btn btn-danger">
                                <i class="fas fa-close"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- List --}}
            <div class="table-responsive card p-3">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Client</th>
                            <th scope="col">Service</th>
                            <th scope="col">Date</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Notes</th>

                            <th scope="col" class="text-center">Actions</th>
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

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.appointments.edit', $appointment) }}"
                                            class="btn btn-warning">
                                            <i class="fas fa-pencil"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.appointments.destroy', $appointment) }}"
                                            method="POST" class="delete-form"
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
                                <td colspan="7" class="text-center p-3">No Appointments Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
