@extends('layouts.app')
@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="mb-4">
                <h2 class="mb-0">Sistema di Prenotazioni</h2>
            </header>

            {{-- Content --}}
            <div class="card p-5 mb-4 rounded-3">
                <div class="container py-3">
                    <h3 class="display-5 fw-bold">
                        Benvenuto nel Sistema di Gestione Appuntamenti
                    </h3>

                    <p class="col-md-8 fs-4">Accedi al sistema per gestire i tuoi appuntamenti, visualizzare il calendario e organizzare i servizi offerti.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg"
                        type="button">Accedi</a>
                </div>
            </div>
        </div>
    </section>
@endsection
