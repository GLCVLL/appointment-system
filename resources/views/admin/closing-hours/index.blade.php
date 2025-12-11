@extends('layouts.app')

@section('title', ' - ' . __('closing_hours.title'))


@section('content')
<section class="py-4 px-md-4">

    <div class="container-fluid">

        {{-- Header --}}
        <header class="d-flex justify-content-between align-items-center mb-4">

            {{-- Title --}}
            <h2 class="mb-0">{{ __('closing_hours.title') }}</h2>

            {{-- Add Closing Hour --}}
            <a href="{{ route('admin.closing-hours.create') }}" class="btn btn-success btn-circle">
                <i class="fas fa-plus fa-lg"></i>
            </a>
        </header>

        {{-- List --}}
        <div class="table-responsive card p-3">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">{{ __('closing_hours.date') }}</th>
                        <th scope="col" class="text-center">{{ __('closing_hours.start_time') }}</th>
                        <th scope="col" class="text-center">{{ __('closing_hours.end_time') }}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($closingHours as $closingHour)
                    <tr>

                        <td>{{ $closingHour->getDate('date', 'd/m/Y') }}</td>

                        <td class="text-center">{{ $closingHour->getDate('start_time', 'H:i') }}</td>

                        <td class="text-center">{{ $closingHour->getDate('end_time', 'H:i') }}</td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex justify-content-end gap-2">

                                {{-- Edit --}}
                                <a href="{{ route('admin.closing-hours.edit', $closingHour) }}"
                                    class="btn btn-warning">
                                    <i class="fas fa-pencil"></i>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.closing-hours.destroy', $closingHour) }}"
                                    method="POST" class="delete-form"
                                    data-modal-name="{{ $closingHour->getDate('date', 'd/m/Y') }}">
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
                        <td colspan="4" class="text-center p-3">{{ __('closing_hours.no_closing_hours') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($closingHours->hasPages())
        <div class="px-4">
            {{ $closingHours->links() }}
        </div>
        @endif

    </div>
</section>
@endsection

@section('scripts')
@vite(['resources/js/commons/modal-delete.js'])
@endsection