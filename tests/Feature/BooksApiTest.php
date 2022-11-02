<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books()
    {
        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ]);
    }

    /** @test */
    function can_get_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    function can_create_books()
    {
        
        $this->postJson(route('books.store'), [])->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => "New book"
        ])->assertJsonFragment([
            'title' => "New book"
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'New book'
        ]);
    }

    /** @test */
    function can_update_books()
    {
        
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [
            'title' => 'Editaded book',
        ])->assertJsonFragment([
            'title' => 'Editaded book',
        ]);
    }

    /** @test */
    function can_delete_books()
    {
        
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))->assertNoContent();

        $this->assertDatabaseCount('books', 0);    
    }
}