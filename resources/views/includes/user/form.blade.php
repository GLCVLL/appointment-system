@php
$validationMessages = [
    'name' => ['required' => __('users.validation.name_required'), 'string' => __('users.validation.name_string'), 'max' => __('users.validation.name_max')],
    'email' => ['required' => __('users.validation.email_required'), 'email' => __('users.validation.email_email'), 'max' => __('users.validation.email_max')],
    'password' => ['required' => __('users.validation.password_required'), 'string' => __('users.validation.password_string'), 'min' => __('users.validation.password_min')],
    'phone_number' => ['string' => __('users.validation.phone_string')]
];
@endphp

@if ($user->exists)
    <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.users.update', $user) }}"
        enctype="multipart/form-data" novalidate
        data-validation-messages='@json($validationMessages)'>
        @method('PUT')
    @else
        <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.users.store') }}"
            enctype="multipart/form-data" novalidate
            data-validation-messages='@json($validationMessages)'>
@endif
@csrf

<div class="row">

    {{-- User Name --}}
    <div class="col-12 mb-4">
        <label for="name" class="form-label fs-5">{{ __('users.name') }}</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $user->name) }}" placeholder="{{ __('users.name_placeholder') }}" required>

        @error('name')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="name-error" class="invalid-feedback"></span>
    </div>

    {{-- User Email --}}
    <div class="col-12 mb-4">
        <label for="email" class="form-label fs-5">{{ __('users.email') }}</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            value="{{ old('email', $user->email) }}" placeholder="{{ __('users.email_placeholder') }}" required>

        @error('email')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="email-error" class="invalid-feedback"></span>
    </div>

    {{-- Phone Number --}}
    <div class="col-12 mb-4">
        <label for="phone_number" class="form-label fs-5">{{ __('users.phone') }}</label>
        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
            name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="{{ __('users.phone_placeholder') }}"
            required>

        @error('phone_number')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="phone_number-error" class="invalid-feedback"></span>
    </div>

    {{-- Password --}}
    <div class="col-12 mb-4">
        <label for="password" class="form-label fs-5">{{ __('auth.password') }}</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
            name="password" placeholder="{{ __('auth.password_placeholder') }}">

        @error('password')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="password-error" class="invalid-feedback"></span>
    </div>

    {{-- Confirm Password --}}
    <div class="col-12 mb-4">
        <label for="password_confirmation" class="form-label fs-5">{{ __('auth.confirm_password') }}</label>
        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
            id="password_confirmation" name="password_confirmation" placeholder="{{ __('auth.confirm_password_placeholder') }}">

        @error('password_confirmation')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="password_confirmation-error" class="invalid-feedback"></span>
    </div>

    {{-- Submit Button --}}
    <div class="col-12">
        <button class="btn btn-primary">{{ __('common.confirm') }}</button>
    </div>

</div>
</form>
