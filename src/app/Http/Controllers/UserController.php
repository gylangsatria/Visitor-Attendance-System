<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('access:1,2'); // Only admin and editor
    }

    public function index()
    {
        $user = auth()->user();
        
        // Jika Editor, hanya lihat user dengan level > 1 (tidak bisa lihat Admin)
        if ($user->access_level === 2) {
            $users = User::where('access_level', '>', 1)
                ->orderBy('access_level')
                ->paginate(10);
        } else {
            // Admin bisa lihat semua user
            $users = User::orderBy('access_level')->paginate(10);
        }
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Editor tidak bisa membuat user baru
        if (auth()->user()->access_level === 2) {
            abort(403, 'Editor cannot create new users.');
        }
        
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Editor tidak bisa menyimpan user baru
        if (auth()->user()->access_level === 2) {
            abort(403, 'Editor cannot create new users.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'access_level' => 'required|in:2,3,4',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();
        
        // Admin bisa edit semua
        if ($currentUser->access_level === 1) {
            return view('users.edit', compact('user'));
        }
        
        // Editor: hanya bisa edit user dengan level 3 atau 4 (Viewer/Guest)
        if ($currentUser->access_level === 2) {
            if ($user->access_level === 1) {
                abort(403, 'Editor cannot edit Admin users.');
            }
            if ($user->access_level === 2) {
                abort(403, 'Editor cannot edit other Editor users.');
            }
            if ($user->access_level >= 3) {
                return view('users.edit', compact('user'));
            }
        }
        
        abort(403, 'Unauthorized access.');
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();
        
        // Admin bisa update semua
        if ($currentUser->access_level === 1) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'access_level' => 'required|in:2,3,4',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500'
            ]);
            
            $user->update($validated);
            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        }
        
        // Editor: hanya bisa update user level 3 atau 4
        if ($currentUser->access_level === 2) {
            // Cek apakah user yang diedit adalah level 1 atau 2
            if ($user->access_level === 1 || $user->access_level === 2) {
                abort(403, 'Editor cannot update Admin or Editor users.');
            }
            
            // Editor hanya bisa update level 3 atau 4, dan tidak bisa mengubah access_level
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500'
            ]);
            
            // Editor tidak bisa mengubah access_level, tetap di level 3 atau 4
            $user->update($validated);
            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        }
        
        abort(403, 'Unauthorized access.');
    }

    public function destroy(User $user)
    {
        $currentUser = auth()->user();
        
        // Cegah menghapus diri sendiri
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot delete your own account!');
        }
        
        // Hanya Admin yang bisa delete user
        if ($currentUser->access_level !== 1) {
            return back()->with('error', 'Only Admin can delete users.');
        }
        
        // Admin tidak bisa delete Admin lain (opsional)
        if ($user->access_level === 1 && $currentUser->id !== $user->id) {
            return back()->with('error', 'Cannot delete other Admin users.');
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}