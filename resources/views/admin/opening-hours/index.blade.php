@extends('layouts.app')

@section('title', ' - Opening Hours')


@section('content')
    <section class="container-fluid py-4">

        {{-- Header --}}
        <header class="d-flex justify-content-between align-items-center mb-3">

            {{-- Title --}}
            <h2 class="mb-0">Opening Hours</h2>

            {{-- Add Business Hours --}}
            <a href="{{ route('admin.opening-hours.create') }}" class="btn btn-success btn-circle">
                <i class="fas fa-plus fa-lg"></i>
            </a>
        </header>

        <hr class="mb-4">

        {{-- List --}}
        <div class="table-responsive px-4">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th scope="col">Day</th>
                        <th scope="col" class="text-center">Opening Time</th>
                        <th scope="col" class="text-center">Closing Time</th>
                        <th scope="col" class="text-center">Break Start</th>
                        <th scope="col" class="text-center">Break End</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($openingHours as $openingHour)
                        <tr>


                            <td>{{ $openingHour->day }}</td>

                            <td class="text-center">{{ $openingHour->getDate('opening_time', 'H:i') }}</td>

                            <td class="text-center">{{ $openingHour->getDate('closing_time', 'H:i') }}</td>

                            <td class="text-center">{{ $openingHour->getDate('break_start', 'H:i') }}</td>

                            <td class="text-center">{{ $openingHour->getDate('break_end', 'H:i') }}</td>

                            {{-- Actions --}}
                            <td>
                                <div class="d-flex justify-content-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.opening-hours.edit', $openingHour) }}" class="btn btn-warning">
                                        <i class="fas fa-pencil"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.opening-hours.destroy', $openingHour) }}" method="POST"
                                        class="delete-form" data-modal-name="{{ $openingHour->day }}">
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
                            <td colspan="6" class="text-center p-3">No results</td>
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
