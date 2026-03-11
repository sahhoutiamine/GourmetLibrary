<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $cat1 = Category::where('name', 'Pâtisserie Française')->first();
        
        $book1 = Book::create([
            'category_id' => $cat1->id,
            'title' => 'Le Grand Manuel du Pâtissier',
            'author' => 'Mélanie Dupuis',
            'isbn' => '9782501103756',
            'total_copies' => 3,
            'available_copies' => 3,
            'published_at' => '2014-01-01'
        ]);

        for ($i=0; $i < 3; $i++) {
            BookCopy::create([
                'book_id' => $book1->id,
                'condition' => $i == 0 ? 'degraded' : 'good',
                'status' => 'available'
            ]);
        }

        $cat2 = Category::where('name', 'Cuisine Italienne')->first();
        $book2 = Book::create([
            'category_id' => $cat2->id,
            'title' => 'On va déguster l\'Italie',
            'author' => 'François-Régis Gaudry',
            'isbn' => '9782501151801',
            'total_copies' => 2,
            'available_copies' => 2,
            'published_at' => '2020-11-25'
        ]);

        for ($i=0; $i < 2; $i++) {
            BookCopy::create([
                'book_id' => $book2->id,
                'condition' => 'good',
                'status' => 'available'
            ]);
        }
    }
}
