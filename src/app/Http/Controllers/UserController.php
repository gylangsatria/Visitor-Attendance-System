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

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Base query
        if ($user->access_level === 2) {
            // Editor: hanya lihat user dengan level > 1 (tidak bisa lihat Admin)
            $query = User::where('access_level', '>', 1);
        } else {
            // Admin bisa lihat semua user
            $query = User::query();
        }
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by access level
        if ($request->filled('access_level')) {
            // Editor tidak bisa filter untuk access_level 1
            if ($user->access_level === 2 && $request->access_level == 1) {
                // Skip filter atau bisa diabaikan
            } else {
                $query->where('access_level', $request->access_level);
            }
        }
        
        // Order by access level then by name
        $query->orderBy('access_level')->orderBy('name');
        
        // Paginate with 15 items per page and preserve query string
        $users = $query->paginate(15)->appends($request->query());
        
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
                'access_level' => 'required|in:1,2,3,4',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500'
            ]);
            
            // Admin bisa update password jika diisi
            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'min:8|confirmed'
                ]);
                $validated['password'] = Hash::make($request->password);
            }
            
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
            
            // Editor bisa update password jika diisi
            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'min:8|confirmed'
                ]);
                $validated['password'] = Hash::make($request->password);
            }
            
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
        
        // Admin tidak bisa delete Admin lain (opsional, bisa disesuaikan)
        if ($user->access_level === 1 && $currentUser->id !== $user->id) {
            return back()->with('error', 'Cannot delete other Admin users.');
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
    
    // Optional: Method untuk reset password (bonus)
    public function resetPassword(Request $request, User $user)
    {
        $currentUser = auth()->user();
        
        // Hanya Admin yang bisa reset password
        if ($currentUser->access_level !== 1) {
            abort(403, 'Only Admin can reset user passwords.');
        }
        
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return back()->with('success', 'Password reset successfully for ' . $user->name);
    }
    
    // Optional: Method untuk bulk delete (bonus)
    public function bulkDelete(Request $request)
    {
        $currentUser = auth()->user();
        
        // Hanya Admin yang bisa bulk delete
        if ($currentUser->access_level !== 1) {
            abort(403, 'Only Admin can perform bulk delete.');
        }
        
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);
        
        // Filter out current user and other admins
        $userIds = array_filter($request->user_ids, function($id) use ($currentUser) {
            $user = User::find($id);
            return $user && $user->id !== $currentUser->id && $user->access_level !== 1;
        });
        
        if (empty($userIds)) {
            return back()->with('error', 'No valid users selected for deletion.');
        }
        
        $deleted = User::whereIn('id', $userIds)->delete();
        
        return back()->with('success', "Successfully deleted {$deleted} users.");
    }
}