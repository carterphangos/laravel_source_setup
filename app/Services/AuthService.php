<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendPasswordResetEmail;

class AuthService
{
    public function registerUser(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $user;
    }

    public function loginUser(Request $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return;
        }

        $user = User::where('email', $request->email)->first();

        return $user;
    }

    public function updatePasswordUser(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
    }

    public function requestResetPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendEmailResetPassword(Request $request)
    {
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        $token = Str::random(60);
        $expiresAt = Carbon::now('Asia/Ho_Chi_Minh')->addMinutes(5);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
        ]);

        SendPasswordResetEmail::dispatch($token, $expiresAt, $request->email);
    }

    public function createNewPassword($token)
    {
        $passwordReset = DB::table('password_reset_tokens')->where('token', $token)
            ->where('expires_at', '>', Carbon::now('Asia/Ho_Chi_Minh'))->first();
        if ($passwordReset) {
            return true;
        }
        return false;
    }

    public function resetPassword(Request $request)
    {
        $passwordReset = DB::table('password_reset_tokens')->where('token', $request->token)
            ->where('expires_at', '>', Carbon::now('Asia/Ho_Chi_Minh'))->first();

        if ($passwordReset) {
            $user = User::where('email', $passwordReset->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_reset_tokens')->where('token', $request->token)->delete();

            return true;
        }
        return false;
    }
}
