@component('mail::message')
    You are register in online examination system

    Name:{{ $mailData['name'] }}
    Email : {{ $mailData['email'] }}

    Thanks,

    {{ config('app.name') }}
@endcomponent
