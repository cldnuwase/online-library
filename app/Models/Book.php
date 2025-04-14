<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //specify fillable columns
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'copies_available'
    ];

    /**
     * Find or create a book, updating copies if it already exists
     */
    public static function findOrCreateWithCopies(array $attributes, int $copies = 1): Book
    {
        $book = static::where('isbn', $attributes['isbn'])->first();

        if ($book) {
            // Book exists, update copies
            $book->copies_available += $copies;
            // Update other attributes if needed
            $book->title = $attributes['title'];
            $book->author = $attributes['author'];
            if (isset($attributes['description'])) {
                $book->description = $attributes['description'];
            }
            $book->save();
            return $book;
        }

        // Create new book with specified copies
        $attributes['copies_available'] = $copies;
        return static::create($attributes);
    }

    /**
     * Add copies to an existing book
     */
    public function addCopies(int $count = 1): void
    {
        $this->increment('copies_available', $count);
    }

    /**
     * Remove copies from an existing book
     */
    public function removeCopies(int $count = 1): bool
    {
        if ($this->copies_available >= $count) {
            $this->decrement('copies_available', $count);
            return true;
        }
        return false;
    }
}
