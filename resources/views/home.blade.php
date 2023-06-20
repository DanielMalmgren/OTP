@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            @if($user->isAdmin)
                <div class="card">
                    <div class="card-body">

                        <form method="post" action="{{action('OTPController@activate')}}" accept-charset="UTF-8">
                            @csrf

                            <label for="username">Användarnamn för personen som ska ha OTP-dosan</label>
                            <div class="mb-3">
                                <input id="username" name="username" required minlength="7" maxlength="9" class="form-control" value="{{old('username')}}">
                            </div>

                            <label for="serial">OTP-dosans serienummer (står på dosans baksida)</label>
                            <div class="mb-3">
                                <input id="serial" name="serial" required minlength="12" maxlength="12" class="form-control" value="{{old('serial')}}">
                            </div>

                            <button class="btn btn-primary" type="submit">Aktivera</button>
                        </form>

                    </div>
                </div>
                <br>
            @endif

        </div>
    </div>
</div>

@endsection
