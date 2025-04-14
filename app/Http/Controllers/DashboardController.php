<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // Get user's borrowed books
        $borrowedBooks = Borrow::where('user_id', auth()->id())
            ->with('book')
            ->orderBy('borrow_date', 'desc')
            ->get();

        // Get available books with search functionality
        $query = Book::where('copies_available', '>', 0);
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $availableBooks = $query->orderBy('title')->paginate(10);

        return view('dashboard.user', compact('borrowedBooks', 'availableBooks'));
    }
}
