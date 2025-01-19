<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'active');

        if ($filter == 'terminated') {
            $users = User::where('gekuendigt', 1)->where('bewerber', 0)->get();
        } elseif ($filter == 'applicants') {
            $users = User::where('bewerber', 1)->get();
        } else {
            $users = User::where('gekuendigt', 0)->where('bewerber', 0)->get();
        }

        $roles = Role::all();
        return view('admin.member.team', compact('users', 'roles'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.member.profile', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'umail' => 'required|string|email|max:255|unique:users',
            'nummer' => 'required|string|max:255',
            'kontonummer' => 'required|string|max:255',
            'role_id' => 'required|integer|exists:roles,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bewerber' => 'required|boolean',
        ]);

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'umail' => $request->umail,
            'nummer' => $request->nummer,
            'kontonummer' => $request->kontonummer,
            'role_id' => $request->role_id,
            'password' => Hash::make('password'), // Set a default password or handle password input
            'profile_image' => $profileImagePath,
            'bewerber' => $request->bewerber,
        ]);

        return redirect()->route('users.index')->with('success', 'Member added successfully.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'umail' => 'required|string|max:255',
            'nummer' => 'required|string|max:255',
            'kontonummer' => 'required|string|max:255',
            'gekuendigt' => 'nullable|boolean',
            'bewerber' => 'nullable|boolean',
        ]);

        $profileImagePath = $user->profile_image;
        if ($request->hasFile('profile_image')) {
            // Delete the old profile image if it exists
            if ($profileImagePath) {
                Storage::disk('public')->delete($profileImagePath);
            }
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'umail' => $request->umail,
            'nummer' => $request->nummer,
            'kontonummer' => $request->kontonummer,
            'profile_image' => $profileImagePath,
            'gekuendigt' => $request->has('gekuendigt') ? 1 : 0,
            'bewerber' => $request->has('bewerber') ? 1 : 0,
        ]);

        return redirect()->route('users.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Member deleted successfully.');
    }
}