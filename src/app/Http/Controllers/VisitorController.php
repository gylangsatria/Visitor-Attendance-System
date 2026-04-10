<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin() || $user->isEditor()) {
            $visitors = Visitor::with('registrar')
                ->orderBy('check_in_time', 'desc')
                ->paginate(15);
        } else {
            $visitors = Visitor::where('registered_by', $user->id)
                ->orderBy('check_in_time', 'desc')
                ->paginate(15);
        }
        
        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        return view('visitors.create');
    }

    public function store(Request $request)
    {
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
        return view('visitors.show', compact('visitor'));
    }

    public function checkOut(Visitor $visitor)
    {
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