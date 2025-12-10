<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ResponseController;
use App\Jobs\ForgetPasswordJob;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Jobs\WelcomeMail;
use App\Jobs\LoginJob;
use App\Models\PasswordReset;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;



class AuthController extends ResponseController
{


    public function studentRegister()
    {
        if (Auth::check()) {
            return back();
        } else {
            return view('auth.register');
        }
    }

    public function storeRegister(Request $request)
    {
        DB::beginTransaction();
        try {

            $validation = Validator::make($request->all(), [
                'name' => 'required|string|min:3',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'confirm_password' => 'required|same:password'
            ]);

            if ($validation->fails()) {

                return $this->send_error($validation->errors(), "Validation Error");
            }
            $student = $request->all();
            $student['password'] = Hash::make($request->password);
            if ($student) {
                DB::commit();
                $user = User::create($student);
                WelcomeMail::dispatch($user);
                return $this->send_success($user, "User Regsitration sucessfully !");
            } else {
                DB::rollBack();
                return $this->send_error($student, "Student Not Register Successfully !");
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->fail_api($e->getMessage(), "Student Not Register Successfully !");
        }
    }

    public function studentLogin()
    {
        if (Auth::check()) {
            return back();
        } else {
            return view('auth.login');
        }
    }

    public function storeLogin(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ]);
            if ($validation->fails()) {
                return $this->send_error($validation->errors(), "Validator Error");
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                $user = User::where('id', Auth::user()->id)->first();
                LoginJob::dispatch($user);
                session()->flash('success', 'Login Successfully');
                if (Auth::user()->user_id == 0) {
                    return response()->json(['redirect_url' => route('student_home')]);
                } else {
                    return response()->json(['redirect_url' => route('admin_home')]);
                }
            } else {
                return $this->send_error("Not login", "Student Creaditional are wrong so not login");
            }
        } catch (\Exception $e) {

            return $this->fail_api($e->getMessage(), "Student Not Login Successfully !");
        }
    }
    public function logout(Request $request)
    {
        if (Auth::check()) {
            // $request->session::flash();
            Auth::logout();
            return redirect()->route('login');
        } else {
            return back();
        }
    }

    public function forgetPassword()
    {
        return view('auth.forget_password');
    }

    public function resetPassword(Request $request)
    {
        $resetUser = PasswordReset::where('token', $request->token)->first();

        if (isset($request->token) && isset($resetUser)) {
            $user = User::where('email', $resetUser->email)->first();

            return view('auth.reset_password', compact('user'));
        } else {
            abort(404);
        }
    }

    public function forgetPasswordStore(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validation->fails()) {
                return $this->send_error($validation->errors(), "Validator Error");
            }
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $token = Str::random(50);
                ForgetPasswordJob::dispatch($user, $token);
                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(['email' => $request->email], ['email' => $user->email, 'token' => $token, 'created_at' => $datetime]);
                return $this->send_success("success", "Please check your mail to reser your password");
            } else {
                return $this->send_error("Error", "Email Not exist !");
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->fail_api($e->getMessage(), "Student Not Login Successfully !");
        }
    }

    public function resetPasswordStore(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'password' => 'required|min:6',
                'password_confirmation' => 'required|same:password'
            ]);
            if ($validation->fails()) {
                return $this->send_error($validation->errors(), "Validator Error");
            }
            $user = User::where('id', $request->id)->first();
            User::where('id', $request->id)->update([
                'password' => Hash::make($request->password)
            ]);

            PasswordReset::where('email', $user->email)->delete();
            return $this->send_success("success", "Password Reset Successfully!");
        } catch (\Exception $e) {
            return $this->fail_api($e->getMessage(), "Api Fail");
        }
    }
}
