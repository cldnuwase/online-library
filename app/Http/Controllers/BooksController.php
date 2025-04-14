<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            return redirect()->route('dashboard');
        }

        $books = Book::select('id', 'title', 'author', 'isbn', 'copies_available')
            ->latest()
            ->paginate(10);

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:13',
            'description' => 'nullable|string',
            'copies' => 'required|integer|min:1'
        ]);

        try {
            Book::findOrCreateWithCopies($validated, $validated['copies']);
            return redirect()->route('admin.books.index')
                ->with('success', 'Book added successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error adding book. Please try again.');
        }
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => [
                'required',
                'string',
                'max:13',
                Rule::unique('books')->ignore($book->id)
            ],
            'description' => 'nullable|string',
            'copies_available' => 'required|integer|min:0'
        ]);

        try {
            $book->update($validated);
            return redirect()->route('admin.books.index')
                ->with('success', 'Book updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating book. Please try again.');
        }
    }

    public function destroy(Book $book)
    {
        try {
            $book->delete();
            return redirect()->route('admin.books.index')
                ->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error deleting book. Please try again.');
        }
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function addCopies(Request $request, Book $book)
    {
        $validated = $request->validate([
            'copies' => 'required|integer|min:1'
        ]);

        $book->addCopies($validated['copies']);
        return redirect()->back()->with('success', 'Copies added successfully.');
    }

    public function removeCopies(Request $request, Book $book)
    {
        $validated = $request->validate([
            'copies' => 'required|integer|min:1'
        ]);

        if ($book->removeCopies($validated['copies'])) {
            return redirect()->back()->with('success', 'Copies removed successfully.');
        }

        return redirect()->back()->with('error', 'Not enough copies available to remove.');
    }
}
