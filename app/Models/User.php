<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get all borrows for the user.
     */
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * Get active borrows (not returned) for the user.
     */
    public function activeBorrows()
    {
        return $this->borrows()->whereNull('return_date');
    }

    /**
     * Check if user has already borrowed a specific book and not returned it.
     */
    public function hasActiveBorrow($bookId)
    {
        return $this->activeBorrows()->where('book_id', $bookId)->exists();
    }
}
