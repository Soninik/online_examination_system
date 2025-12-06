@extends('layout.layout')
@section('title', 'Login')
@section('contant')

    <style>
        .error {
            color: red;
            font-weight: bold;
        }
    </style>

    <div class="container">
        <h4>Reset Password</h4>
        <form id="resetPasswordForm">
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label">Password</label>
                <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                <span class="error" id="error-password"></span>
            </div>
            <div class="mb-4">
                <label for="email" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    placeholder="Enter Password Confirmation">
                <span class="error" id="error-password_confirmation"></span>
            </div>
            <span class="showerror error"></span> <br>
            <button class="btn btn-md btn-success loginBtn">Reset Password</button>
            <a href="{{ route('login_view') }}" class="btn btn-md btn-dark">Login</a>
        </form>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            $(".loginBtn").click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ route('reset_password_store') }}",
                    data: $("#resetPasswordForm").serialize(),
                    datatype: "json",
                    success: function(res) {
                        if (res.success == false) {
                            getError(res);
                        } else {
                            alert(res.msg);
                            window.location.href = "{{ route('login_view') }}";
                        }
                    }
                });
            })
        });

        function getError(error) {
            if (error.data == "Validator Error") {
                const error_msg = error.msg;
                Object.keys(error_msg).forEach((field) => {

                    $("#error-" + field).text("");
                    error_msg[field].forEach((msg) => {
                        $("#error-" + field).text(msg);
                    });
                });
            } else {
                $(".showerror").text(error.data)
            }
        }
    </script>
@endpush
