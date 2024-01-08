@extends('layouts.app')

@section('title', ' - Closed Days')


@section('content')
    <section class="container-fluid py-4">

        {{-- Header --}}
        <header class="d-flex justify-content-between align-items-center mb-3">

            {{-- Title --}}
            <h2 class="mb-0">Closed Days</h2>

            {{-- Add Business Hours --}}
            <a href="{{ route('admin.closed-days.create') }}" class="btn btn-success btn-circle">
                <i class="fas fa-plus fa-lg"></i>
            </a>
        </header>

        <hr class="mb-4">

        {{-- List --}}
        <div class="table-responsive px-4">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($closedDays as $closedDay)
                        <tr>


                            <td>{{ $closedDay->getDate('date', 'd/m') }}</td>

                            {{-- Actions --}}
                            <td>
                                <div class="d-flex justify-content-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.closed-days.edit', $closedDay) }}" class="btn btn-warning">
                                        <i class="fas fa-pencil"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.closed-days.destroy', $closedDay) }}" method="POST"
                                        class="delete-form" data-modal-name="{{ $closedDay->getDate('date', 'd/m') }}">
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
                            <td colspan="2" class="text-center p-3">No results</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($closedDays->hasPages())
            <div class="px-4">
                {{ $closedDays->links() }}
            </div>
        @endif

    </section>
@endsection

@section('scripts')
    @vite(['resources/js/commons/modal-delete'])
@endsection
