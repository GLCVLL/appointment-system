@if ($service->exists)
    <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.services.update', $service) }}"
        enctype="multipart/form-data" novalidate>
        @method('PUT')
    @else
        <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.services.store') }}"
            enctype="multipart/form-data" novalidate>
@endif
@csrf

<div class="row">

    {{-- Service Name --}}
    <div class="col-12 mb-4">
        <label for="name" class="form-label fs-5">Service Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $service->name) }}" placeholder="Enter service name" required>

        @error('name')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="name-error" class="invalid-feedback"></span>
    </div>

    {{-- Service Duration --}}
    <div class="col-12 mb-4">
        <label for="duration" class="form-label fs-5">Duration</label>
        <input type="text" class="form-control @error('duration') is-invalid @enderror" id="duration"
            name="duration" value="{{ old('duration', $service->duration) }}" placeholder="HH:MM:SS" required>

        @error('duration')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="duration-error" class="invalid-feedback"></span>
    </div>

    {{-- Service Price --}}
    <div class="col-12 mb-4">
        <label for="price" class="form-label fs-5">Service Price</label>
        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
            value="{{ old('price', $service->price) }}" required>

        @error('price')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="price-error" class="invalid-feedback"></span>
    </div>

    {{-- Availability --}}
    <div class="col-12 mb-4">
        <label for="is_available" class="form-label fs-5">Availability</label>
        <select class="form-select @error('is_available') is-invalid @enderror" id="is_available" name="is_available">
            <option value="1" {{ old('is_available', $service->is_available) == 1 ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('invalid-feedback', $service->is_available) == 0 ? 'selected' : '' }}>No
            </option>
        </select>

        @error('is_available')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="is_available-error" class="invalid-feedback"></span>
    </div>

    {{-- Submit Button --}}
    <div class="col-12">
        <button class="btn btn-primary">Confirm</button>
    </div>

</div>
</form>
