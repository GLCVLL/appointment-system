@php
$validationMessages = [
    'date' => ['required' => __('closed_days.validation.date_required'), 'date' => __('closed_days.validation.date_format')]
];
@endphp

@if ($closedDay->exists)
    <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.closed-days.update', $closedDay) }}"
        enctype="multipart/form-data" novalidate
        data-validation-messages='@json($validationMessages)'>
        @method('PUT')
    @else
        <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.closed-days.store') }}"
            enctype="multipart/form-data" novalidate
            data-validation-messages='@json($validationMessages)'>
@endif
@csrf

<div class="row">

    {{-- Date --}}
    <div class="col-12 col-md-7 mb-4">

        <label for="date" class="form-label fs-5">{{ __('closed_days.date') }}</label>

        <input id="date" type="date" class="form-control @error('date') is-invalid @enderror"
            value="{{ old('opening_time', $closedDay->date) }}" name="date">
        @error('date')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="date-error" class="invalid-feedback"></span>
    </div>


    {{-- # Submit --}}
    <div class="col-12">
        <button class="btn btn-primary">{{ __('common.confirm') }}</button>
    </div>

</div>
</form>
