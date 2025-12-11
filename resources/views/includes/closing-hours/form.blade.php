@php
use Carbon\Carbon;

$validationMessages = [
'date' => [
'required' => __('closing_hours.validation.date_required'),
'date' => __('closing_hours.validation.date_format'),
'after_or_equal' => __('closing_hours.validation.date_future'),
],
'start_time' => [
'required' => __('closing_hours.validation.start_time_required'),
'date_format' => __('closing_hours.validation.start_time_format'),
],
'end_time' => [
'required' => __('closing_hours.validation.end_time_required'),
'date_format' => __('closing_hours.validation.end_time_format'),
'after' => __('closing_hours.validation.end_time_after'),
],
];

// Format times for display
$startTimeFormatted = $closingHour->start_time ? Carbon::parse($closingHour->start_time)->format('H:i') : '';
$endTimeFormatted = $closingHour->end_time ? Carbon::parse($closingHour->end_time)->format('H:i') : '';
@endphp

@if ($closingHour->exists)
<form id="validation-form" class="card p-3" method="POST"
    action="{{ route('admin.closing-hours.update', $closingHour) }}" enctype="multipart/form-data" novalidate
    data-validation-messages='@json($validationMessages)'
    data-times-url="{{ route('admin.closing-hours.times') }}">
    @method('PUT')
    @else
    <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.closing-hours.store') }}"
        enctype="multipart/form-data" novalidate data-validation-messages='@json($validationMessages)'
        data-times-url="{{ route('admin.closing-hours.times') }}">
        @endif
        @csrf

        <div class="row">

            {{-- Date --}}
            <div class="col-12 col-md-7 mb-4">

                <label for="date" class="form-label fs-5">{{ __('closing_hours.date') }}</label>

                <input id="date" type="date" class="form-control @error('date') is-invalid @enderror"
                    value="{{ old('date', $closingHour->date ? $closingHour->date->format('Y-m-d') : '') }}" name="date"
                    min="{{ date('Y-m-d') }}">
                <small>{{ __('closing_hours.date_note') }}</small>
                @error('date')
                <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                @enderror
                <span id="date-error" class="invalid-feedback"></span>
            </div>

            {{-- Closing Hours --}}
            <div class="col-12 mb-4">

                <p class="fs-5">{{ __('closing_hours.closing_period') }}</p>

                <div class="row">
                    <div class="col-6">
                        <label for="start_time" class="form-label">{{ __('closing_hours.start_time') }}</label>

                        <select id="start_time" class="form-select @error('start_time') is-invalid @enderror" name="start_time">
                            <option value="">----</option>
                            @foreach ($timeArray as $time)
                            <option @if (old('start_time', $startTimeFormatted)==$time['value']) selected @endif
                                value="{{ $time['value'] }}">
                                {{ $time['text'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('start_time')
                        <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                        @enderror
                        <span id="start_time-error" class="invalid-feedback"></span>
                    </div>

                    <div class="col-6">
                        <label for="end_time" class="form-label">{{ __('closing_hours.end_time') }}</label>

                        <select id="end_time" class="form-select @error('end_time') is-invalid @enderror" name="end_time">
                            <option value="">----</option>
                            @foreach ($timeArray as $time)
                            <option @if (old('end_time', $endTimeFormatted)==$time['value']) selected @endif
                                value="{{ $time['value'] }}">
                                {{ $time['text'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('end_time')
                        <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                        @enderror
                        <span id="end_time-error" class="invalid-feedback"></span>
                    </div>
                </div>

            </div>

            {{-- # Submit --}}
            <div class="col-12">
                <button class="btn btn-primary">{{ __('common.confirm') }}</button>
            </div>

        </div>
    </form>