<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function register(array $request): User
    {
        $user = User::query()->create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $this->assignTokenToUser($user);
        return $user;
    }

    public function login(array $request): User
    {
        abort_if(!Auth::attempt($request), Response::HTTP_UNAUTHORIZED,
            __('Credential does not match with our records.'));

        $user = User::query()
            ->where('email', $request['email'])
            ->firstOrFail();

        $this->assignTokenToUser($user);
        return $user;
    }

    private function assignTokenToUser(User $user): void
    {
        $token = $user->createToken('API Token')->plainTextToken;
        $user->setAttribute('access_token', $token);
    }

    public function forgotPassword(array $validated): void
    {
        $status = Password::sendResetLink($validated);

        if ($status === Password::RESET_LINK_SENT) {
            return;
        }

        switch ($status) {
            case Password::INVALID_USER:
                abort(Response::HTTP_BAD_REQUEST, __('User does not exist.'));
            case Password::RESET_THROTTLED:
                abort(Response::HTTP_TOO_MANY_REQUESTS, __('Too many requests. Please try again later.'));
            default:
                abort(Response::HTTP_BAD_REQUEST, __('Unable to send password reset email.'));
        }
    }

    public function resetPassword(array $request): void
    {
        $status = Password::reset(
            $request,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new ResetPassword($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return;
        }

        switch ($status) {
            case Password::INVALID_TOKEN:
                abort(Response::HTTP_BAD_REQUEST, __('This password reset token is invalid.'));
            case Password::INVALID_USER:
                abort(Response::HTTP_BAD_REQUEST, __('No user found with this email address.'));
            default:
                abort(Response::HTTP_BAD_REQUEST, __('Unable to reset password. Please try again.'));
        }
    }
}
