@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Aktivering lyckades</div>
                <div class="card-body">

                    Aktiveringen är genomförd,<br>
                    OTP-dosan är nu färdig att använda.<br><br>

                    PIN-kod: {{$pin}}<br><br>

                    OBS! PIN-koden ovan visas bara en gång!<br>
                    Uppmana gärna användaren att testa OTP-dosan på direkten<br>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
