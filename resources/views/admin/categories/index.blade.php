@extends('layouts.app')

@section('title', ' - Categories')

@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="d-flex justify-content-between align-items-center mb-4">

                {{-- Title --}}
                <h2 class="mb-0">Categories</h2>

                {{-- Add Category --}}
                <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-circle">
                    <i class="fas fa-plus fa-lg"></i>
                </a>
            </header>

            {{-- List --}}
            <div class="table-responsive card p-3">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>

                                {{-- Actions --}}
                                <td>
                                    <div class="d-flex justify-content-end gap-2">

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                                            <i class="fas fa-pencil"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            class="delete-form" data-modal-name="{{ $category->name }}">
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
                                <td colspan="2" class="text-center p-3">No Categories Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- PAGINATION --}}
                @if ($categories->hasPages())
                    {{ $categories->links() }}
                @endif
            </div>

        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/commons/modal-delete'])
@endsection
