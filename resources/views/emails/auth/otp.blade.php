@extends('layouts.emails.index')

@section('content')
    <p>
        Hi {{$otp->user->full_name}},
    </p>
    <p>
        Your OTP is <b>{{$otp->code}}</b>
    </p>
    <p>Please do not share with anyone. Expiry in 15 minutes from now</p>
@endsection
