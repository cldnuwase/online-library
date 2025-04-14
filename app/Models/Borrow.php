<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'notes',
        'phone',
        'return_requested'
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue()
    {
        return !$this->return_date && $this->due_date < Carbon::now();
    }
}
