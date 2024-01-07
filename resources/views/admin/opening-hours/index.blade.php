@extends('layouts.app')

@section('content')
    <section class="container py-4">

        {{-- Header --}}
        <header class="d-flex justify-content-between align-items-center mb-3">

            {{-- Title --}}
            <h2 class="mb-0">Opening Hours</h2>

            {{-- Add Business Hours --}}
            <a href="{{ route('admin.opening-hours.create') }}" class="btn btn-success rounded-circle">
                <i class="fas fa-plus fa-lg"></i>
            </a>
        </header>

        {{-- List --}}
        <div class="table-responsive">
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
                    @forelse ($opening_hours as $opening_hour)
                        <tr>


                            <td>{{ $opening_hour->day }}</td>

                            <td class="text-center">{{ $opening_hour->getDate('opening_time', 'H:i') }}</td>

                            <td class="text-center">{{ $opening_hour->getDate('closing_time', 'H:i') }}</td>

                            <td class="text-center">{{ $opening_hour->getDate('break_start', 'H:i') }}</td>

                            <td class="text-center">{{ $opening_hour->getDate('break_end', 'H:i') }}</td>

                            {{-- Actions --}}
                            <td>
                                <div class="d-flex justify-content-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.opening-hours.edit', $opening_hour) }}"
                                        class="btn btn-warning">
                                        <i class="fas fa-pencil"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.opening-hours.destroy', $opening_hour) }}" method="POST"
                                        class="delete-form" data-modal-name="{{ $opening_hour->id }}">
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