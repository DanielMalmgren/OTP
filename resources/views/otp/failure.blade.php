@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Aktivering misslyckades</div>
                <div class="card-body">

                    Någonting gick fel med aktiveringen,<br>
                    vänligen kontakta Itsam Support.<br>

                    @if(isset($error) && $error != '')
                        <br>
                        Felkod:<br>
                        {{$error}}
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
