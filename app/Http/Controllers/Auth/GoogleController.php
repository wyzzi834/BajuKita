<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirect()
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('galat', 'Login Google belum dikonfigurasi');
        }

        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        try {
            $driver = Socialite::driver('google')->stateless();

            if (app()->environment('local')) {
                $driver->setHttpClient(new Client(['verify' => false]));
            }

            $googleUser = $driver->user();
        } catch (Throwable $exception) {
            Log::error('Google login failed', [
                'message' => $exception->getMessage(),
                'class' => $exception::class,
            ]);

            $message = app()->environment('local')
                ? 'Login Google gagal: ' . $exception->getMessage()
                : 'Login Google gagal, silakan coba lagi';

            return redirect()->route('login')->with('galat', $message);
        }

        $user = User::firstOrNew(['email' => $googleUser->getEmail()]);
        $user->name = $user->name ?: ($googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User');
        $user->google_id = $googleUser->getId();
        $user->email_verified_at = $user->email_verified_at ?: now();

        if (empty($user->password)) {
            $user->password = Hash::make(Str::random(32));
        }

        $user->save();

        Auth::login($user, true);

        return match ($user->role) {
            'admin' => redirect()->route('admin'),
            'operator' => redirect()->route('operator.order.index'),
            default => redirect()->route('home'),
        };
    }
}
