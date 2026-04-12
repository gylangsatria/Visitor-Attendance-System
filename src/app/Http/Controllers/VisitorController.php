<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function __construct()
    {
        // Guest (level 4) hanya bisa view, tidak bisa create, edit, checkout
        $this->middleware('access:1,2,3')->only(['create', 'store', 'checkOut']);
    }
    
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin() || $user->isEditor()) {
            $visitors = Visitor::with('registrar')
                ->orderBy('check_in_time', 'desc')
                ->paginate(15);
        } else {
            // Viewer (level 3) dan Guest (level 4) hanya lihat milik sendiri
            $visitors = Visitor::where('registered_by', $user->id)
                ->orderBy('check_in_time', 'desc')
                ->paginate(15);
        }
        
        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        // Cek apakah Guest (level 4)
        if (auth()->user()->access_level === 4) {
            abort(403, 'Guest users cannot register visitors.');
        }
        
        return view('visitors.create');
    }

    public function store(Request $request)
    {
        // Cek apakah Guest (level 4)
        if (auth()->user()->access_level === 4) {
            abort(403, 'Guest users cannot register visitors.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'id_card_number' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:500',
            'person_to_meet' => 'required|string|max:255'
        ]);

        $validated['check_in_time'] = now();
        $validated['registered_by'] = auth()->id();
        $validated['status'] = 'active';

        Visitor::create($validated);

        return redirect()->route('visitors.index')->with('success', 'Visitor registered successfully!');
    }

    public function show(Visitor $visitor)
    {
        $user = auth()->user();
        
        // Guest (level 4) hanya bisa lihat visitor milik sendiri
        if ($user->access_level === 4 && $visitor->registered_by !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        
        // Viewer (level 3) hanya bisa lihat visitor milik sendiri
        if ($user->access_level === 3 && $visitor->registered_by !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('visitors.show', compact('visitor'));
    }

    public function checkOut(Visitor $visitor)
    {
        $user = auth()->user();
        
        // Guest (level 4) tidak bisa checkout
        if ($user->access_level === 4) {
            abort(403, 'Guest users cannot check out visitors.');
        }
        
        // Viewer (level 3) hanya bisa checkout visitor milik sendiri
        if ($user->access_level === 3 && $visitor->registered_by !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($visitor->status === 'completed') {
            return back()->with('error', 'Visitor has already checked out!');
        }

        $visitor->update([
            'check_out_time' => now(),
            'status' => 'completed'
        ]);

        return redirect()->route('visitors.index')->with('success', 'Visitor checked out successfully!');
    }
}