@component('mail::message')
    Forget Password to online examination system
    </br> <strong>
        Pls Click the forget password link and change your password

        <a href="{{ route('resetPassword', ['token' => $token]) }}" class="btn btn-md btn-dark"> Forget Password </a>
    </strong>

    you are use this system now Thanks,
@endcomponent
