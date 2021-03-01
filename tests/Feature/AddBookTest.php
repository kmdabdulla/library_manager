<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AddBookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_addBook_unauthorized_access()
    {
        $this->json('POST', '/api/book', ['Accept' => 'application/json'])
            ->assertStatus(401);
    }

    public function test_addBook_no_inputs() {

        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $this->json('POST', '/api/book', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "title" => ["The title field is required."],
                "isbn" => ["The isbn field is required."],
                "publishedDate" => ["Date of publication is required."],
            ]
        ]);
    }

    public function test_addBook_invalid_isbn10() {

        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $bookData = [
            "title" => "test",
            "isbn" => "0978194986",
            "publishedDate" => "2014-12-12"
        ];
        $this->json('POST', '/api/book', $bookData,['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "isbn" => ["Invalid ISBN-10."],
            ]
        ]);
        $bookData['isbn'] = "097819498C";
        $this->json('POST', '/api/book', $bookData,['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "isbn" => ["Invalid ISBN-10."],
            ]
        ]);
        $bookData['isbn'] = "978194987";
        $this->json('POST', '/api/book', $bookData,['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "isbn" => ["The isbn must be 10 characters."],
            ]
        ]);
    }

    public function test_addBook_invalid_published_date() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $bookData = [
            "title" => "test",
            "isbn" => "0978194986",
            "publishedDate" => "12-12-1994"
        ];
        $this->json('POST', '/api/book', $bookData,['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "publishedDate" => ["Date of publication format should be YYYY-MM-DD."],
            ]
        ]);
        $bookData['publishedDate'] = "December 12, 1994";
        $this->json('POST', '/api/book', $bookData,['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "publishedDate" => ["Date of publication format should be YYYY-MM-DD."],
            ]
        ]);
    }

    public function test_addBook_title_max_length() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $bookData = [
            "title" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit",
            "isbn" => "0978194986",
            "publishedDate" => "1994-12-12"
        ];
        $this->json('POST', '/api/book', $bookData,['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "title" => ["The title may not be greater than 255 characters."],
            ]
        ]);
    }

    public function test_addBook_success_case() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $bookData = [
            "title" => "Little Red Riding Hood",
            "isbn" => "0978171349",
            "publishedDate" => "1697-10-01"
        ];
        $this->json('POST', '/api/book', $bookData,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonFragment([
            "message" => "Book added to the library successfully."
            ]);
    }

    public function test_addBook_book_already_exists() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $bookData = [
            "title" => "Cinderella",
            "isbn" => "0978171349",
            "publishedDate" => "1697-10-01"
        ];
        $this->json('POST', '/api/book', $bookData,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonFragment([
            "message" => "The ISBN is associated with other book."
            ]);
    }


}
