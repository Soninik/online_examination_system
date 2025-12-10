@extends('layout.layout')
@section('title', 'register')
@section('contant')
    <style>
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
    <div class="container">
        <h4>Student Register</h4>
        <form id="registerForm">
            @csrf
            <div class="mb-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                    placeholder="Enter Name">
                <span class="error" id="error-name"></span>
            </div>
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('name') }}"
                    placeholder="Enter Email">
                <span class="error" id="error-email"></span>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control"placeholder="Enter Password">
                <span class="error" id="error-password"></span>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password"
                    class="form-control"placeholder="Enter Confirm Password">
                <span class="error" id="error-confirm_password"></span>
            </div>
            <button class="btn btn-md btn-success registerBtn">Register</button>
            <a href="{{ route('login') }}" class="btn btn-md btn-dark">Login</a>
        </form>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            $(".registerBtn").click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ route('store_register') }}",
                    data: $("#registerForm").serialize(),
                    datatype: "json",
                    success: function(res) {
                        if (res.success == false) {
                            send_error(res)

                        } else {
                            $("#registerForm")[0].reset();
                            alert(res.msg)
                            window.location.href = "{{ route('login') }}";
                        }
                    }
                });
            });
        });

        function send_error(error) {
            $(".error").text("")
            const errorMsg = error.msg;
            Object.keys(errorMsg).forEach(field => {
                errorMsg[field].forEach(msg => {
                    $("#error-" + field).html(msg)
                });
            });
        }
    </script>
@endpush
