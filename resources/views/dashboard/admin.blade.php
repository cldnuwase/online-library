@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Admin Dashboard</h2>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Books</h5>
                                    <h2 class="card-text">{{ $totalBooks }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Borrows</h5>
                                    <h2 class="card-text">{{ $totalBorrows }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Active Loans</h5>
                                    <h2 class="card-text">{{ $activeLoans }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Overdue Books</h5>
                                    <h2 class="card-text">{{ $overdueBorrows }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Recent Borrows</h4>
                            @if ($recentBorrows->isEmpty())
                                <div class="alert alert-info">
                                    No recent borrows available.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Book</th>
                                                <th>Borrower</th>
                                                <th>Borrow Date</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentBorrows as $borrow)
                                                <tr>
                                                    <td>{{ $borrow->book->title }}</td>
                                                    <td>{{ $borrow->borrower_name }}</td>
                                                    <td>{{ $borrow->borrow_date->format('Y-m-d') }}</td>
                                                    <td>{{ $borrow->due_date->format('Y-m-d') }}</td>
                                                    <td>
                                                        @if ($borrow->return_date)
                                                            <span class="badge bg-success">Returned</span>
                                                        @elseif ($borrow->status === 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif ($borrow->status === 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @elseif ($borrow->status === 'rejected')
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @elseif ($borrow->due_date < now())
                                                            <span class="badge bg-danger">Overdue</span>
                                                        @else
                                                            <span class="badge bg-info">Borrowed</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.borrows.show', $borrow) }}" class="btn btn-info btn-sm">View</a>
                                                        @if($borrow->status === 'pending')
                                                            <form action="{{ route('admin.borrows.approve', $borrow) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                            </form>
                                                            <form action="{{ route('admin.borrows.reject', $borrow) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                            </form>
                                                        @elseif($borrow->status === 'approved' && !$borrow->return_date)
                                                            <form action="{{ route('admin.borrows.return', $borrow) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to mark this book as returned?')">Return</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>Book List</h4>
                                <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Add New Book</a>
                            </div>
                            @if ($books->isEmpty())
                                <div class="alert alert-info">
                                    No books available in the library.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Author</th>
                                                <th>ISBN</th>
                                                <th>Copies Available</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($books as $book)
                                                <tr>
                                                    <td>{{ $book->title }}</td>
                                                    <td>{{ $book->author }}</td>
                                                    <td>{{ $book->isbn }}</td>
                                                    <td>{{ $book->copies_available }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning btn-sm">Edit</a>
                                                            <a href="{{ route('admin.books.show', $book) }}" class="btn btn-info btn-sm">View</a>
                                                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?')">Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $books->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
