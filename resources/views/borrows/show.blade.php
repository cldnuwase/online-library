@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Borrow Details</h5>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Book Information</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Title:</strong> {{ $borrow->book->title }}</p>
                                    <p><strong>Author:</strong> {{ $borrow->book->author }}</p>
                                    <p><strong>ISBN:</strong> {{ $borrow->book->isbn }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Borrower Information</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> {{ $borrow->user ? $borrow->user->name : 'N/A' }}</p>
                                    <p><strong>Email:</strong> {{ $borrow->user ? $borrow->user->email : 'N/A' }}</p>
                                    <p><strong>Phone:</strong> {{ $borrow->user ? $borrow->user->phone : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-muted">Borrow Details</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Borrow Date:</strong> {{ $borrow->borrow_date->format('Y-m-d') }}</p>
                                    <p><strong>Due Date:</strong> {{ $borrow->due_date->format('Y-m-d') }}</p>
                                    <p><strong>Return Date:</strong> 
                                        @if ($borrow->status === 'pending')
                                            Not yet approved or rejected
                                        @elseif ($borrow->status === 'rejected')
                                            No return needed
                                        @elseif ($borrow->status === 'approved')
                                            {{ $borrow->return_date ? $borrow->return_date->format('Y-m-d') : 'Not returned yet' }}
                                            @elseif ($borrow->status === 'returned')
                                            Book already Returned
                                        @endif
                                    </p>
                                    <p><strong>Status:</strong> 
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
                                        @endif
                                    </p>
                                    <p><strong>Notes:</strong> {{ $borrow->notes ?? 'Borrow request submitted' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <a href="{{ route('borrows.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            @if ($borrow->status === 'pending')
                                <form action="{{ route('admin.borrows.approve', $borrow) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this borrow request?')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.borrows.reject', $borrow) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this borrow request?')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            @elseif ($borrow->status === 'approved' && $borrow->return_requested && !$borrow->return_date)
                                <form action="{{ route('borrows.return', $borrow) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to mark this book as returned?')">
                                        <i class="fas fa-undo"></i> Return
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
