@if ($user->exists)
    <form id="validation-form" class="px-4" method="POST" action="{{ route('admin.users.update', $user) }}"
        enctype="multipart/form-data" novalidate>
        @method('PUT')
    @else
        <form id="validation-form" class="px-4" method="POST" action="{{ route('admin.users.store') }}"
            enctype="multipart/form-data" novalidate>
@endif
@csrf

<div class="row">

    {{-- User Name --}}
    <div class="col-12 mb-4">
        <label for="name" class="form-label fs-5">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $user->name) }}" placeholder="Enter user name" required>

        @error('name')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="name-error" class="invalid-feedback"></span>
    </div>

    {{-- User Email --}}
    <div class="col-12 mb-4">
        <label for="email" class="form-label fs-5">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            value="{{ old('email', $user->email) }}" placeholder="Enter email address" required>

        @error('email')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="email-error" class="invalid-feedback"></span>
    </div>

    {{-- Phone Number --}}
    <div class="col-12 mb-4">
        <label for="phone_number" class="form-label fs-5">Phone Number</label>
        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
            name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Enter Phone number"
            required>

        @error('phone_number')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="phone_number-error" class="invalid-feedback"></span>
    </div>

    {{-- Password --}}
    <div class="col-12 mb-4">
        <label for="password" class="form-label fs-5">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
            name="password" placeholder="Enter password">

        @error('password')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="password-error" class="invalid-feedback"></span>
    </div>

    {{-- Confirm Password --}}
    <div class="col-12 mb-4">
        <label for="password_confirmation" class="form-label fs-5">Confirm Password</label>
        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
            id="password_confirmation" name="password_confirmation" placeholder="Confirm password">

        @error('password_confirmation')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="password_confirmation-error" class="invalid-feedback"></span>
    </div>

    {{-- Submit Button --}}
    <div class="col-12">
        <button class="btn btn-primary">Confirm</button>
    </div>

</div>
</form>
