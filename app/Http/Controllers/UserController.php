<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Protect route: Only Admin
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        // Fetch all users except Super Admin just in case it exists, but rule says only Admin & Teknisi.
        // We will just fetch all users ordered by role and name.
        $users = User::orderBy('role')->orderBy('nama_jelas')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'nama_jelas' => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users,username',
            'password'   => 'required|string|min:6',
            'role'       => 'required|in:Admin,Teknisi',
        ]);

        User::create([
            'id_user'    => (string) Str::uuid(),
            'nama_jelas' => $request->nama_jelas,
            'username'   => $request->username,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id_user)
    {
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id_user);

        $rules = [
            'nama_jelas' => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users,username,' . $id_user . ',id_user',
            'role'       => 'required|in:Admin,Teknisi',
        ];

        // If password field is filled, validate it
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        $request->validate($rules);

        $user->nama_jelas = $request->nama_jelas;
        $user->username = $request->username;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id_user)
    {
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id_user);

        // Prevent self-deletion
        if ($user->id_user === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
