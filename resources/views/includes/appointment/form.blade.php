@if ($appointment->exists)
<form id="validation-form" class="card p-3" method="POST"
    action="{{ route('admin.appointments.update', $appointment) }}" enctype="multipart/form-data" novalidate>
    @method('PUT')
    @else
    <form id="validation-form" class="card p-3" method="POST" action="{{ route('admin.appointments.store') }}"
        enctype="multipart/form-data" novalidate>
        @endif
        @csrf

        <div class="row">

            {{-- User --}}
            <div class="col-12 col-md-5 mb-4">

                <label for="user_id" class="form-label fs-5">Client</label>

                <select id="user_id" class="form-select @error('user_id') is-invalid @enderror" name="user_id">
                    <option value="">-- Choose a Client --</option>
                    @foreach ($users as $user)
                    <option @if (old('user_id', $appointment->user_id) == $user->id) selected @endif value="{{ $user->id }}">
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
                <p class="fs-5 mb-2">Services</p>

                <div id="services"
                    class="d-flex flex-wrap gap-3 rounded p-1 @error('services') border border-danger @enderror">
                    @foreach ($services as $service)
                    <div>
                        <input class="form-check-input" type="checkbox" @if (in_array($service->id, old('services', $selectedServices ?? []))) checked @endif
                        id="service-{{ $service->id }}" value="{{ $service->id }}" name="services[]">
                        <label class="form-check-label" for="service-{{ $service->id }}">{{ $service->name }}</label>
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
                <label for="date" class="form-label fs-5">Appointment Date</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date"
                    value="{{ old('date', $appointment->date ?? '') }}" required>

                @error('date')
                <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                @enderror
                <span id="date-error" class="invalid-feedback"></span>
            </div>


            {{-- Appointment Start Time --}}
            <div class="col-6 mb-4">

                <label for="start_time" class="form-label fs-5">Start Time</label>

                <select id="start_time" class="form-select @error('start_time') is-invalid @enderror" name="start_time">
                    <option value="">----</option>

                    @foreach ($time_array as $time)
                    <option @if (old('start_time', $appointment->getDate('start_time', 'H:i')) == $time['value']) selected @endif value="{{ $time['value'] }}">
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
                <label for="end_time" class="form-label fs-5">End Time</label>
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
                <label for="notes" class="form-label fs-5">Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                    placeholder="Enter additional notes">{{ old('notes', $appointment->notes ?? '') }}</textarea>

                @error('notes')
                <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                @enderror
                <span id="notes-error" class="invalid-feedback"></span>
            </div>


            {{-- Submit --}}
            <div class="col-12">
                <button class="btn btn-primary">Confirm</button>
            </div>

        </div>
    </form>