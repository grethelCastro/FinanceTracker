<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the profile settings page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('perfil', compact('user'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if (!empty($validatedData['new_password'])) {
            $user->password = Hash::make($validatedData['new_password']);
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Update the user's preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePreferences(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'currency' => 'required|string|in:NIO,USD,EUR',
            'date_format' => 'required|string|in:d/m/Y,m/d/Y,Y-m-d',
            'dark_mode' => 'sometimes|boolean',
            'notifications' => 'sometimes|boolean',
        ]);

        $user->currency = $validatedData['currency'];
        $user->date_format = $validatedData['date_format'];
        $user->dark_mode = $request->boolean('dark_mode');
        $user->notifications = $request->boolean('notifications');

        $user->save();

        return back()->with('success', 'Preferencias actualizadas correctamente.');
    }
}