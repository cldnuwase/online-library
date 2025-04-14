<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            return redirect()->route('dashboard');
        }

        $query = Borrow::with(['book', 'user']);

        if (request()->has('status')) {
            $status = request('status');
            if (in_array($status, ['pending', 'approved', 'rejected'])) {
                $query->where('status', $status);
            }
        }

        $borrows = $query->latest()->paginate(10);

        return view('borrows.index', compact('borrows'));
    }

    public function create()
    {
        $books = Book::where('copies_available', '>', 0)->get();
        return view('borrows.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'phone' => 'required|string|min:10|max:15|regex:/^[0-9+\-() ]+$/',
        ]);

        $book = Book::findOrFail($request->book_id);
        $user = Auth::user();

        // Check if user already has an active borrow for this book
        $activeBorrow = Borrow::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->first();

        if ($activeBorrow) {
            $errorMessage = 'You are not allowed to borrow the same book. Please return your current copy first before borrowing again. Current status: ' . ucfirst($activeBorrow->status);
            return redirect()->route('borrows.create')->with('error', $errorMessage);
        }

        if ($book->copies_available <= 0) {
            return redirect()->route('borrows.create')->with('error', 'This book is not available for borrowing at the moment.');
        }

        $borrow = Borrow::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(4),
            'phone' => $request->phone,
            'status' => 'pending',
            'notes' => 'Borrow request submitted'
        ]);

        // Decrease available copies
        $book->decrement('copies_available');

        return redirect()->route('dashboard')->with('success', 'Your borrow request has been submitted and is currently pending approval.');
    }

    public function show(Borrow $borrow)
    {
        return view('borrows.show', compact('borrow'));
    }

    public function update(Request $request, Borrow $borrow)
    {
        if (Auth::id() !== $borrow->user_id) {
            return back()->with('error', 'You are not authorized to update this borrow record.');
        }

        $request->validate([
            'phone' => 'required|string|min:10|max:15',
        ]);

        $borrow->update([
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Your phone number has been updated successfully.');
    }

    public function destroy(Borrow $borrow)
    {
        if (Auth::id() !== $borrow->user_id) {
            return back()->with('error', 'You are not authorized to delete this borrow record.');
        }

        if ($borrow->status === 'approved') {
            return back()->with('error', 'You cannot cancel an approved borrow request.');
        }

        // Increase available copies
        $borrow->book->increment('copies_available');
        $borrow->delete();

        return back()->with('success', 'Your borrow request has been cancelled successfully.');
    }

    public function approve(Borrow $borrow)
    {
        if ($borrow->status !== 'pending') {
            return back()->with('error', 'This borrow request cannot be approved.');
        }

        $borrow->update([
            'status' => 'approved',
            'notes' => 'Borrow request approved by admin'
        ]);

        return back()->with('success', 'Borrow request approved successfully.');
    }

    public function reject(Borrow $borrow)
    {
        if ($borrow->status !== 'pending') {
            return back()->with('error', 'This borrow request cannot be rejected.');
        }

        // Increase available copies since the request is rejected
        $borrow->book->increment('copies_available');

        $borrow->update([
            'status' => 'rejected',
            'notes' => 'Borrow request rejected by admin'
        ]);

        return back()->with('success', 'Borrow request rejected successfully.');
    }

    public function requestReturn(Borrow $borrow)
    {
        if (Auth::id() !== $borrow->user_id) {
            return back()->with('error', 'You are not authorized to request return for this book.');
        }

        if ($borrow->status !== 'approved') {
            return back()->with('error', 'This book cannot be returned.');
        }

        if ($borrow->return_requested) {
            return back()->with('error', 'Return request is already pending.');
        }

        $borrow->update([
            'return_requested' => true,
            'notes' => 'Return requested by user'
        ]);

        return back()->with('success', 'Return request submitted successfully. Waiting for admin approval.');
    }

    public function approveReturn(Borrow $borrow)
    {
        if (!auth()->user()->is_admin) {
            return back()->with('error', 'You are not authorized to approve returns.');
        }

        if (!$borrow->return_requested) {
            return back()->with('error', 'This book has not been requested for return.');
        }

        // Increase available copies
        $borrow->book->increment('copies_available');

        $borrow->update([
            'return_date' => now(),
            'status' => 'returned',
            'notes' => 'Return approved by admin'
        ]);

        return back()->with('success', 'Book return approved successfully.');
    }

    public function return(Borrow $borrow)
    {
        if (!auth()->user()->is_admin) {
            return back()->with('error', 'You are not authorized to return books.');
        }

        if ($borrow->status !== 'approved') {
            return back()->with('error', 'This book cannot be returned.');
        }

        if ($borrow->return_date) {
            return back()->with('error', 'This book has already been returned.');
        }

        // Increase available copies
        $borrow->book->increment('copies_available');

        $borrow->update([
            'return_date' => now(),
            'status' => 'returned',
            'notes' => 'Book returned by admin'
        ]);

        return back()->with('success', 'Book has been returned successfully.');
    }
}
