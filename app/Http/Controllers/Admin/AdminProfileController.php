<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', [
            'user' => Auth::user()
        ]);
    }

    public function update(\App\Http\Requests\Admin\UpdateAdminProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update($request->validated());

        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(\App\Http\Requests\Admin\UpdateAdminPasswordRequest $request)
    {
        $validated = $request->validated();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
