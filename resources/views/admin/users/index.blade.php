@extends('layouts.app')

@section('title', ' - ' . __('users.clients'))

@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="d-flex justify-content-between align-items-center mb-4">
                {{-- Title --}}
                <h2 class="mb-0">{{ __('users.clients') }}</h2>
                {{-- Add User --}}
                <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-circle">
                    <i class="fas fa-plus fa-lg"></i>
                </a>
            </header>

            {{-- List --}}
            <div class="table-responsive card p-3">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('users.name') }}</th>
                            <th scope="col" class="d-none d-sm-table-cell">{{ __('users.email') }}</th>
                            <th scope="col" class="text-center">{{ __('users.phone') }}</th>
                            <th scope="col" class="d-none d-sm-table-cell">{{ __('users.role') }}</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td class="d-none d-sm-table-cell">{{ $user->email }}</td>
                                <td class="text-center">{{ $user->phone_number ?? '-' }}</td>
                                <td class="d-none d-sm-table-cell">{{ ucfirst($user->role) }}</td>
                                <td>
                                    {{-- Actions --}}
                                    <div class="d-flex justify-content-end gap-2">
                                        {{-- Block/Unblock --}}
                                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn {{ $user->blocked ? 'btn-success' : 'btn-dark' }}" 
                                                    type="submit" 
                                                    title="{{ $user->blocked ? __('blacklist.unblock') : __('blacklist.block') }}">
                                                <i class="fas {{ $user->blocked ? 'fa-unlock' : 'fa-skull' }}"></i>
                                            </button>
                                        </form>
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="delete-form" data-modal-name="{{ $user->name }}">
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
                                <td colspan="4" class="text-center p-3">{{ __('users.no_users') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- PAGINATION --}}
                @if ($users->hasPages())
                    {{ $users->links() }}
                @endif
            </div>

        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/commons/modal-delete.js'])
@endsection
