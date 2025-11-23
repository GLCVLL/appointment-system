@if ($category->exists)
    <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.categories.update', $category) }}"
        enctype="multipart/form-data" novalidate>
        @method('PUT')
    @else
        <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.categories.store') }}"
            enctype="multipart/form-data" novalidate>
@endif
@csrf

<div class="row">

    {{-- Category Name --}}
    <div class="col-12 mb-4">
        <label for="name" class="form-label fs-5">{{ __('categories.name_label') }}</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $category->name) }}" placeholder="{{ __('categories.name_placeholder') }}" required>

        @error('name')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="name-error" class="invalid-feedback"></span>
    </div>

    {{-- # Submit --}}
    <div class="col-12">
        <button class="btn btn-primary">{{ __('common.confirm') }}</button>
    </div>

</div>
</form>
