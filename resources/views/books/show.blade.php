@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Book Details</h2>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <h5>Title</h5>
                        <p class="lead">{{ $book->title }}</p>
                    </div>

                    <div class="mb-3">
                        <h5>Author</h5>
                        <p class="lead">{{ $book->author }}</p>
                    </div>

                    <div class="mb-3">
                        <h5>ISBN</h5>
                        <p>{{ $book->isbn }}</p>
                    </div>

                    <div class="mb-3">
                        <h5>Description</h5>
                        <p>{{ $book->description ?: 'No description available.' }}</p>
                    </div>

                    <div class="mb-3">
                        <h5>Copies Available</h5>
                        <p>{{ $book->copies_available }}</p>
                    </div>

                    <div class="mb-3">
                        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Back to List</a>
                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
