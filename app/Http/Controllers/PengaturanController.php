<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PengaturanController extends Controller
{
    // Tampilkan semua user
    public function index()
    {
        $users = User::all();
        return view('pengaturan.index', compact('users'));
    }

    // Form edit user tertentu
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pengaturan.edit', compact('user'));
    }

    // Proses update user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('pengaturan.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);

        if (auth()->id() == $user->id) {
        return back()->with('error', 'Kamu tidak bisa menghapus dirimu sendiri.');
        }

        $user->delete();

        return redirect()->route('pengaturan.index')->with('success', 'User berhasil dihapus.');
    }

    public function create()
    {
        return view('pengaturan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
        ]);

        return redirect()->route('pengaturan.index')->with('success', 'User baru berhasil ditambahkan.');
    }

}