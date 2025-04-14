<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalBorrows = Borrow::count();
        $activeLoans = Borrow::whereIn('status', ['pending', 'approved', 'borrowed'])->count();
        $overdueBooks = Borrow::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->count();

        $borrows = Borrow::with(['book', 'user'])
            ->whereIn('status', ['pending', 'approved', 'borrowed', 'rejected'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalBorrows',
            'activeLoans',
            'overdueBooks',
            'borrows'
        ));
    }
}
