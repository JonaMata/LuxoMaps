<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Str;

class GoogleController extends Controller
{
    public function oneTap(Request $request) {
        $client = new Client([
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
        ]);

        $payload = $client->verifyIdToken($request->input('credential'));

        if (!$payload['email'].endsWith('@luxovi.us')) {
            return redirect()->route('login')->with('status', 'You must use a @luxovi.us email address to sign in.');
        }

        $user = User::updateOrCreate([
            'email' => $payload['email'],
        ], [
            'name' => $payload['name'],
            'password' => Hash::make(Str::password()),
        ]);

        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
