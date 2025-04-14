@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Borrowed Books</h5>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('admin.borrows.index') }}" method="GET" class="me-3">
                            <div class="input-group">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </form>
                        <a href="{{ route('borrows.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Borrow Book
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($borrows->isEmpty())
                        <div class="alert alert-info">
                            No borrow records available.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Borrower</th>
                                        <th>Email</th>
                                        <th>Borrow Date</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($borrows as $borrow)
                                        <tr>
                                            <td>{{ $borrow->book->title }}</td>
                                            <td>{{ $borrow->user ? $borrow->user->name : 'N/A' }}</td>
                                            <td>{{ $borrow->user ? $borrow->user->email : 'N/A' }}</td>
                                            <td>{{ $borrow->borrow_date->format('Y-m-d') }}</td>
                                            <td>{{ $borrow->due_date->format('Y-m-d') }}</td>
                                            <td>{{ $borrow->return_date ? $borrow->return_date->format('Y-m-d') : 'Not returned' }}</td>
                                            <td>
                                                @if ($borrow->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif ($borrow->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @elseif ($borrow->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif ($borrow->return_date)
                                                    <span class="badge bg-info">Returned</span>
                                                @elseif ($borrow->due_date < now())
                                                    <span class="badge bg-danger">Overdue</span>
                                                @else
                                                    <span class="badge bg-primary">Borrowed</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('borrows.show', $borrow) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                @if ($borrow->status === 'pending')
                                                    <form action="{{ route('admin.borrows.approve', $borrow) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this borrow request?')">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.borrows.reject', $borrow) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this borrow request?')">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    </form>
                                                @elseif ($borrow->status === 'approved' && $borrow->return_requested && !$borrow->return_date)
                                                    <form action="{{ route('borrows.return', $borrow) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to mark this book as returned?')">
                                                            <i class="fas fa-undo"></i> Return
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $borrows->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
