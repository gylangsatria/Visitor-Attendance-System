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
    
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Base query
        if ($user->isAdmin() || $user->isEditor()) {
            $query = Visitor::with('registrar');
        } else {
            // Viewer (level 3) dan Guest (level 4) hanya lihat milik sendiri
            $query = Visitor::where('registered_by', $user->id);
        }
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('person_to_meet', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id_card_number', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range (check_in_time)
        if ($request->filled('start_date')) {
            $query->whereDate('check_in_time', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_time', '<=', $request->end_date);
        }
        
        // Order by latest check in first
        $query->orderBy('check_in_time', 'desc');
        
        // Paginate with 15 items per page and preserve query string
        $visitors = $query->paginate(15)->appends($request->query());
        
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
            'email' => 'nullable|email|max:255',
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
    
    public function export(Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isEditor()) {
            abort(403, 'Unauthorized access.');
        }

        $query = Visitor::with('registrar');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('person_to_meet', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('check_in_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('check_in_time', '<=', $request->end_date);
        }

        $visitors = $query->orderBy('check_in_time', 'desc')->get();
        $filename = 'visitors_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($visitors) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID', 'Name', 'Email', 'Phone', 'ID Card Number', 'Company',
                'Purpose', 'Person to Meet', 'Check In Time', 'Check Out Time',
                'Status', 'Registered By'
            ]);

            foreach ($visitors as $visitor) {
                fputcsv($handle, [
                    $visitor->id,
                    $visitor->name,
                    $visitor->email,
                    $visitor->phone,
                    $visitor->id_card_number,
                    $visitor->company,
                    $visitor->purpose,
                    $visitor->person_to_meet,
                    $visitor->check_in_time,
                    $visitor->check_out_time,
                    $visitor->status,
                    $visitor->registrar->name ?? 'N/A'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}