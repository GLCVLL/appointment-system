@extends('layouts.app')

@section('title', ' - ' . __('common.blacklist'))

@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="d-flex justify-content-between align-items-center mb-4">
                {{-- Title --}}
                <h2 class="mb-0">{{ __('common.blacklist') }}</h2>
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
                                        {{-- Unblock --}}
                                        <form action="{{ route('admin.blacklist.toggle', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-success" type="submit" title="{{ __('blacklist.unblock') }}">
                                                <i class="fas fa-unlock"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-3">{{ __('blacklist.no_blocked_users') }}</td>
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

