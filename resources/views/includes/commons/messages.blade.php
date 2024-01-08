@if (session('messages'))
    {{-- Messages container --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">

        {{-- Messages --}}
        @foreach (session('messages') as $message)
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">

                {{-- Header --}}
                <div class="toast-header bg-dark text-light">
                    <strong class="me-auto">{{ $message['sender'] }}</strong>
                    <small>{{ date('Y/m/d h:m:s', strtotime($message['timestamp'])) }}</small>
                    <button type="button" class="btn btn-sm text-light ms-2" data-bs-dismiss="toast" aria-label="Close">
                        <i class="fas fa-times fa-lg"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="toast-body">

                    {{ $message['content'] }}

                    {{-- Actions --}}
                    @if (isset($message['route']))
                        <div class="mt-2 pt-2 border-top">
                            <a href="{{ $message['route'] }}" class="btn btn-sm btn-success">Vai</a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach


    </div>
@endif
