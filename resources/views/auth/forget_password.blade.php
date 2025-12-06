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
        <h4>Forget Password</h4>
        <form id="forgetPasswordForm">
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('name') }}"
                    placeholder="Enter Email">
                <span class="error" id="error-email"></span>
            </div>
            <span class="showerror error"></span> <br>
            <button class="btn btn-md btn-success loginBtn">Forget Password</button>
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
                    url: "{{ route('forget_password_store') }}",
                    data: $("#forgetPasswordForm").serialize(),
                    datatype: "json",
                    success: function(res) {
                        if (res.success == false) {
                            getError(res);
                        } else {
                            alert(res.msg);
                            {{--  window.location.href = res.redirect_url;  --}}
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
