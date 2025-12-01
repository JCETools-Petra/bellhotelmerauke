<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
// Gate tidak perlu di-import lagi di sini

class UserController extends Controller
{
    public function index()
    {
        // Gate::authorize('admin'); // <-- Baris ini sudah tidak diperlukan
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Gate::authorize('admin'); // <-- Baris ini sudah tidak diperlukan
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            // TAMBAHKAN 'frontoffice' DI SINI
            'role' => ['required', 'in:admin,accounting,frontoffice,user,affiliate'], 
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // Gate::authorize('admin'); // <-- Baris ini sudah tidak diperlukan
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // TAMBAHKAN 'frontoffice' DI SINI JUGA
            'role' => 'required|in:admin,accounting,frontoffice,affiliate,user',
        ]);

        $user->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}