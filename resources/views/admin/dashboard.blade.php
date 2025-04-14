@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Books</h5>
                    <p class="card-text">{{ $totalBooks }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Borrows</h5>
                    <p class="card-text">{{ $totalBorrows }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Loans</h5>
                    <p class="card-text">{{ $activeLoans }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Overdue Books</h5>
                    <p class="card-text">{{ $overdueBooks }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Borrows</h4>
                </div>
                <div class="card-body">
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
                                @foreach($borrows as $borrow)
                                <tr>
                                    <td>{{ $borrow->book->title }}</td>
                                    <td>{{ $borrow->user->name }}</td>
                                    <td>{{ $borrow->borrow_date->format('Y-m-d') }}</td>
                                    <td>{{ $borrow->due_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $borrow->status === 'approved' ? 'success' : 
                                            ($borrow->status === 'pending' ? 'warning' : 
                                            ($borrow->status === 'rejected' ? 'danger' : 
                                            ($borrow->status === 'borrowed' ? 'info' : 'secondary'))) 
                                        }}">
                                            {{ ucfirst($borrow->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                                    data-bs-target="#borrowDetails{{ $borrow->id }}">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            @if($borrow->status === 'pending')
                                                <form action="{{ route('admin.borrows.approve', $borrow->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.borrows.reject', $borrow->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </form>
                                            @elseif($borrow->status === 'approved' && !$borrow->return_date)
                                                <form action="{{ route('admin.borrows.return', $borrow->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-undo"></i> Return
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Borrow Details Modal -->
                                <div class="modal fade" id="borrowDetails{{ $borrow->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Borrow Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6>Book Information</h6>
                                                <p><strong>Title:</strong> {{ $borrow->book->title }}</p>
                                                <p><strong>Author:</strong> {{ $borrow->book->author }}</p>
                                                <p><strong>ISBN:</strong> {{ $borrow->book->isbn }}</p>

                                                <h6 class="mt-3">Borrower Information</h6>
                                                <p><strong>Name:</strong> {{ $borrow->user->name }}</p>
                                                <p><strong>Email:</strong> {{ $borrow->user->email }}</p>
                                                <p><strong>Phone:</strong> {{ $borrow->phone }}</p>

                                                <h6 class="mt-3">Borrow Details</h6>
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
                                                <p><strong>Status:</strong> {{ ucfirst($borrow->status) }}</p>
                                                @if($borrow->notes)
                                                <p><strong>Notes:</strong> {{ $borrow->notes }}</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 