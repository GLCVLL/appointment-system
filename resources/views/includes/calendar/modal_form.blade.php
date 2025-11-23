@php
$validationMessages = [
    'user_id' => ['required' => __('appointments.validation.client_required')],
    'services' => ['required' => __('appointments.validation.service_required')],
    'start_time' => ['required' => __('appointments.validation.start_time_required'), 'time' => __('appointments.validation.start_time_format'), 'after_date_time' => __('appointments.validation.start_time_after')],
    'date' => ['required' => __('appointments.validation.date_required'), 'date' => __('appointments.validation.date_format'), 'after_or_equal' => __('appointments.validation.date_after')],
    'notes' => ['string' => __('appointments.validation.notes_string')]
];
@endphp

<div id="modal-form" class="app-modal modal-full @if (session('modal-error')) has-error @endif">
    <div class="app-modal-content">
        <div class="app-modal-title">
            <span></span>
            <button class="btn app-modal-close" data-close>
                <i class="fas fa-close fa-2xl"></i>
            </button>
        </div>

        <div class="app-modal-body">

            <div class="container-full">

                <form id="validation-form" class="card p-3" method="POST"
                    data-resource-id="{{ session('resource-id') ?? '' }}"
                    action="{{ route('admin.appointments.store') }}" enctype="multipart/form-data" novalidate
                    data-validation-messages='@json($validationMessages)'>
                    @csrf

                    {{-- Edit method input (disconnected) --}}
                    <input id="method" type="hidden" name="_method" value="">

                    <div class="row">

                        {{-- User --}}
                        <div class="col-12 col-md-5 mb-4">

                            <label for="user_id" class="form-label fs-5">{{ __('appointments.client') }}</label>

                            <select id="user_id" class="form-select @error('user_id') is-invalid @enderror"
                                name="user_id">
                                <option value="">{{ __('appointments.choose_client') }}</option>
                                @foreach ($users as $user)
                                <option @if (old('user_id')==$user->id) selected @endif
                                    value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                            @enderror
                            <span id="user_id-error" class="invalid-feedback"></span>
                        </div>


                        {{-- Services --}}
                        <div class="col-12 mb-4">
                            <p class="fs-5 mb-2">{{ __('appointments.services') }}</p>

                            <div id="services"
                                class="d-flex flex-wrap gap-3 rounded p-1 @error('services') border border-danger @enderror">
                                @foreach ($services as $service)
                                <div>
                                    <input class="form-check-input" type="checkbox" id="service-{{ $service->id }}"
                                        @if (in_array($service->id, old('services', $selectedServices ?? []))) checked @endif value="{{ $service->id }}"
                                    name="services[]">
                                    <label class="form-check-label"
                                        for="service-{{ $service->id }}">{{ $service->name }}</label>
                                </div>
                                @endforeach

                            </div>
                            @error('services')
                            <span class="error-message text-danger mt-2" role="alert">{{ $message }}</span>
                            @enderror
                            <span id="services-error" class="invalid-feedback"></span>
                        </div>


                        {{-- Appointment Date --}}
                        <div class="col-12 mb-4">
                            <label for="date" class="form-label fs-5">{{ __('appointments.date') }}</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                id="date" name="date" value="{{ old('date') }}" required>

                            @error('date')
                            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                            @enderror
                            <span id="date-error" class="invalid-feedback"></span>
                        </div>


                        {{-- Appointment Start Time --}}
                        <div class="col-6 mb-4">

                            <label for="start_time" class="form-label fs-5">{{ __('appointments.start_time') }}</label>

                            <select id="start_time" class="form-select @error('start_time') is-invalid @enderror"
                                name="start_time">
                                <option value="">----</option>

                                @foreach ($time_array as $time)
                                <option @if (old('start_time')==$time['value']) selected @endif
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


                        {{-- Appointment End Time --}}
                        <div class="col-6 mb-4">
                            <label for="end_time" class="form-label fs-5">{{ __('appointments.end_time') }}</label>
                            <select id="end_time" class="form-select @error('end_time') is-invalid @enderror" disabled>
                                <option value="">----</option>
                            </select>

                            @error('end_time')
                            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                            @enderror
                            <span id="end_time-error" class="invalid-feedback"></span>
                        </div>


                        {{-- Additional Notes --}}
                        <div class="col-12 mb-4">
                            <label for="notes" class="form-label fs-5">{{ __('appointments.notes') }}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                placeholder="{{ __('appointments.notes_placeholder') }}">{{ old('notes') }}</textarea>

                            @error('notes')
                            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                            @enderror
                            <span id="notes-error" class="invalid-feedback"></span>
                        </div>


                        {{-- Submit --}}
                        <div class="col-12 d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-secondary me-2" data-close>{{ __('common.cancel') }}</button>
                                <button class="btn btn-primary app-modal-submit">{{ __('common.confirm') }}</button>
                            </div>

                            {{-- Delete --}}
                            <button type="button" id="delete-btn" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                    </div>
                </form>

            </div>

        </div>
    </div>
