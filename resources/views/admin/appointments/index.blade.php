@extends('layouts.app')

@section('title', ' - Appointments')

@section('content')
    <section class="container-fluid py-4">

        {{-- Header --}}
        <header class="d-flex justify-content-between align-items-center mb-4">

            {{-- Title --}}
            <h2 class="mb-0">Appointments</h2>

            {{-- Add Category --}}
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-success btn-circle">
                <i class="fas fa-plus fa-lg"></i>
            </a>
        </header>

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
                                @foreach ($appointment->services as $service)
                                    {{ $service->name }} <br>
                                @endforeach
                            </td>
                            <td>{{ $appointment->getDate('date', 'd/m/Y') }}</td>
                            <td>{{ $appointment->getDate('start_time', 'H:i') }}</td>
                            <td>{{ $appointment->getDate('end_time', 'H:i') }}</td>
                            <td>{{ $appointment->notes }}</td>

                            {{-- Actions --}}
                            <td>
                                <div class="d-flex justify-content-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-warning">
                                        <i class="fas fa-pencil"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST"
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
                            <td colspan="2" class="text-center p-3">No Appointments Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </section>
@endsection

@section('scripts')
    @vite(['resources/js/commons/modal-delete'])
@endsection
