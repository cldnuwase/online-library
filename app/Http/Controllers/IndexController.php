<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalBooks = Book::count();
        $availableBooks = Book::where('copies_available', '>', 0)->count();
        $activeBorrows = Borrow::whereNull('return_date')->count();
        $overdueBooks = Borrow::whereNull('return_date')
            ->where('due_date', '<', now())
            ->count();

        // Get recent books
        $recentBooks = Book::latest()
            ->take(5)
            ->get();

        // Get recent borrows
        $recentBorrows = Borrow::with('book')
            ->latest()
            ->take(5)
            ->get();

        return view('index', compact(
            'totalBooks',
            'availableBooks',
            'activeBorrows',
            'overdueBooks',
            'recentBooks',
            'recentBorrows'
        ));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $book = Book::where('title', 'like', "%{$search}%")->first();
        
        if (!$book) {
            return back()->with('error', "We do not have {$search} in our library. Please try searching for another one.");
        }

        if (!auth()->check()) {
            return back()->with('info', "Great! We have {$book->title} available. Please log in to borrow it.");
        }

        return back()->with('book', $book);
    }
}
