@if ($openingHour->exists)
    <form id="validation-form" method="POST" action="{{ route('admin.opening-hours.update', $openingHour) }}"
        enctype="multipart/form-data" novalidate>
        @method('PUT')
    @else
        <form id="validation-form" method="POST" action="{{ route('admin.opening-hours.store') }}"
            enctype="multipart/form-data" novalidate>
@endif
@csrf

<div class="row">

    {{-- Day --}}
    <div class="col-12 col-md-7 mb-4">

        <label for="day" class="form-label fs-5">Day</label>

        <select id="day" class="form-select @error('day') is-invalid @enderror" name="day">
            <option value="">-- Choose a Day --</option>
            @foreach (config('data.day_of_week') as $day)
                <option @if (old('day', $openingHour->day) == $day) selected @endif>
                    {{ $day }}</option>
            @endforeach
        </select>
        @error('day')
            <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
        @enderror
        <span id="day-error" class="error-message"></span>
    </div>


    {{-- Working Time --}}
    <div class="col-12 mb-4">

        <p class="fs-5">Working Time</p>

        <div class="row">
            <div class="col-6">
                <label for="opening_time" class="form-label">Start</label>

                <select id="opening_time" class="form-select @error('opening_time') is-invalid @enderror"
                    name="opening_time">
                    <option value="">----</option>
                    @foreach ($time_array as $time)
                        <option @if (old('opening_time', $openingHour->opening_time) == $time) selected @endif>
                            {{ $time }}</option>
                    @endforeach
                </select>
                @error('opening_time')
                    <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                @enderror
                <span id="opening_time-error" class="error-message"></span>
            </div>

            <div class="col-6">
                <label for="closing_time" class="form-label">End</label>

                <select id="closing_time" class="form-select @error('closing_time') is-invalid @enderror"
                    name="closing_time">
                    <option value="">----</option>
                    @foreach ($time_array as $time)
                        <option @if (old('closing_time', $openingHour->closing_time) == $time) selected @endif>
                            {{ $time }}</option>
                    @endforeach
                </select>
                @error('closing_time')
                    <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                @enderror
                <span id="closing_time-error" class="error-message"></span>
            </div>
        </div>

    </div>


    {{-- Break Time --}}
    <div class="col-12 mb-4">

        <p class="fs-5">Break Time</p>

        <div class="row">
            <div class="col-6">
                <label for="break_start" class="form-label">Start</label>

                <select id="break_start" class="form-select @error('break_start') is-invalid @enderror"
                    name="break_start">
                    <option value="">none</option>
                    @foreach ($time_array as $time)
                        <option @if (old('break_start', $openingHour->break_start) == $time) selected @endif>
                            {{ $time }}</option>
                    @endforeach
                </select>
                @error('break_start')
                    <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                @enderror
                <span id="break_start-error" class="error-message"></span>
            </div>

            <div class="col-6">
                <label for="break_end" class="form-label">End</label>

                <select id="break_end" class="form-select @error('break_end') is-invalid @enderror" name="break_end">
                    <option value="">none</option>
                    @foreach ($time_array as $time)
                        <option @if (old('break_end', $openingHour->break_end) == $time) selected @endif>
                            {{ $time }}</option>
                    @endforeach
                </select>
                @error('break_end')
                    <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                @enderror
                <span id="break_end-error" class="error-message"></span>
            </div>
        </div>

    </div>


    {{-- # Submit --}}
    <div class="col-12">
        <button class="btn btn-primary">Confirm</button>
    </div>

</div>
</form>
