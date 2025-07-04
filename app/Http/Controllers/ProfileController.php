<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the profile settings page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('perfil');
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');


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
        $request->validate([
            'currency' => 'required|string|max:3',
            'dark_mode' => 'required|boolean',
        ]);

        $user = Auth::user();
        $user->currency = $request->input('currency');
        $user->dark_mode = $request->input('dark_mode') === 'on';


        return back()->with('success', 'Preferencias actualizadas correctamente.');
    }
}