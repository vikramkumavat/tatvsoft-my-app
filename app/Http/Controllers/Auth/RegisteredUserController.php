<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dob' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $uploadFile = '';
        if ($request->hasFile('image')) {
            $filenameOrg = $request->file('image')->getClientOriginalName();

            $filename = pathinfo($filenameOrg, PATHINFO_FILENAME);

            $extension = $request->file('image')->getClientOriginalExtension();

            $uploadFile = $filename . '_' . time() . '.' . $extension;

            $request->file('image')->storeAs('/public/images', $uploadFile);
        } else {
            $uploadFile = 'noimage.jpg';
        }

        $dob = $request->dob;
        $role = isset($request->role) && !empty($request->role) ? 1 : 0;

        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dob' => $dob,
            'email' => $request->email,
            'image' => $uploadFile,
            'role' => $role,
            'password' => Hash::make($request->password),
        ];
        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
