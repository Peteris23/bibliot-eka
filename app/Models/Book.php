<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'year',
        'description',
        'available',
        'genre',
        'image',
        'publisher',
        'pages',
        'language',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
