@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- My Dashboard Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h2>My Dashboard</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Welcome, {{ auth()->user()->name }}!</h4>
                        <div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                            <form action="{{ route('profile.destroy') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">Delete Account</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>My Borrowed Books</h5>
                        @if($borrowedBooks->isEmpty())
                            <p>You haven't borrowed any books yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Borrow Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($borrowedBooks as $borrow)
                                            <tr>
                                                <td>{{ $borrow->book->title }}</td>
                                                <td>{{ $borrow->borrow_date->format('Y-m-d') }}</td>
                                                <td>{{ $borrow->due_date->format('Y-m-d') }}</td>
                                                <td>
                                                    @if($borrow->status === 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($borrow->status === 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($borrow->status === 'approved' && !$borrow->return_date && !$borrow->return_requested)
                                                        <form action="{{ route('borrows.request-return', $borrow) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to request to return this book?')">
                                                                Request Return
                                                            </button>
                                                        </form>
                                                    @elseif($borrow->return_requested)
                                                        <span class="badge bg-info">Return Requested</span>
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
            </div>

            <!-- Available Books Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Available Books</h2>
                    <form action="{{ route('dashboard') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search books..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <div class="card-body">
                    @if($availableBooks->isEmpty())
                        <p>No books are currently available.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>ISBN</th>
                                        <th>Available Copies</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availableBooks as $book)
                                        <tr>
                                            <td>{{ $book->title }}</td>
                                            <td>{{ $book->author }}</td>
                                            <td>{{ $book->isbn }}</td>
                                            <td>{{ $book->copies_available }}</td>
                                            <td>
                                                @if($book->copies_available > 0)
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#borrowModal{{ $book->id }}">
                                                        Borrow
                                                    </button>
                                                @else
                                                    <span class="badge bg-secondary">Not Available</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Borrow Modal -->
                                        <div class="modal fade" id="borrowModal{{ $book->id }}" tabindex="-1" aria-labelledby="borrowModalLabel{{ $book->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="borrowModalLabel{{ $book->id }}">Borrow {{ $book->title }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('borrows.store') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                            <div class="mb-3">
                                                                <label for="phone" class="form-label">Phone Number</label>
                                                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Confirm Borrow</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $availableBooks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
