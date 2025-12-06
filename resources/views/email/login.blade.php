@component('mail::message')
    Welcome to online examination system
    </br> Name : <strong> {{ $mailData['name'] }}</strong>
    </br> Email : <strong> {{ $mailData['email'] }}</strong>

    you are use this system now Thanks,
@endcomponent
