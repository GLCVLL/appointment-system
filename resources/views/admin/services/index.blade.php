@extends('layouts.app')

@section('title', ' - Services')

@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="d-flex justify-content-between align-items-center mb-4">
                {{-- Title --}}
                <h2 class="mb-0">Services</h2>
                {{-- Add Service --}}
                <a href="{{ route('admin.services.create') }}" class="btn btn-success btn-circle">
                    <i class="fas fa-plus fa-lg"></i>
                </a>
            </header>

            {{-- List --}}
            <div class="table-responsive card p-3">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col" class="text-center">Duration</th>
                            <th scope="col" class="text-center">Availability</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $service)
                            <tr>
                                <td>{{ $service->name }}</td>
                                <td class="text-center">{{ $service->getDate('duration', 'H:i') }}</td>
                                <td class="text-center">{{ $service->is_available ? 'Yes' : 'No' }}</td>
                                <td class="text-end">
                                    {{-- Actions --}}
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-warning">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                            class="delete-form" data-modal-name="{{ $service->name }}">
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
                                <td colspan="4" class="text-center p-3">No Services Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- PAGINATION --}}
                @if ($services->hasPages())
                    {{ $services->links() }}
                @endif
            </div>

        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/commons/modal-delete.js'])
@endsection
