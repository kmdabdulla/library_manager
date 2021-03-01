<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_no_inputs() {

        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "email" => ["The email field is required."],
                "name" => ["The name field is required."],
                "password" => ["The password field is required."],
                "date_of_birth" => ["Date of Birth is required."],
            ]
        ]);
    }

    public function test_register_invalid_email() {
        $request = [
            "name" => "test",
            "email" => "testgmail.com",
            "password" => "Password1",
            "date_of_birth" => "12-12-1994"
        ];
        $this->json('POST', 'api/register', $request, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "email" => ["The email must be a valid email address."],
            ]
        ]);
    }

    public function test_register_invalid_password() {
        $request = [
            "name" => "test",
            "email" => "test@gmail.com",
            "password" => "Password",
            "date_of_birth" => "1994-12-12"
        ];
        $this->json('POST', 'api/register', $request, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "password" => ["Password should contain at least 1 capital letter and 1 number."],
            ]
        ]);
        $request['password'] = "1234abcd";
        $this->json('POST', 'api/register', $request, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "password" => ["Password should contain at least 1 capital letter and 1 number."],
            ]
        ]);
    }

    public function test_addBook_invalid_date_of_birth() {

        $bookData = [
            "name" => "test",
            "email" => "test@gmail.com",
            "password" => "Igotit123",
            "date_of_birth" => "12-12-1994"
        ];
        $this->json('POST', '/api/register', $bookData,['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "date_of_birth" => ["Date of Birth format should be YYYY-MM-DD."],
            ]
        ]);
        $bookData['publishedDate'] = "December 12, 1994";
        $this->json('POST', '/api/register', $bookData,['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "date_of_birth" => ["Date of Birth format should be YYYY-MM-DD."],
            ]
        ]);
    }

    public function test_register_success_case() {
        $request = [
            "name" => "test",
            "email" => "test@gmail.com",
            "password" => "Password1",
            "date_of_birth" => "1994-12-12"
        ];
        $this->json('POST', 'api/register', $request, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonFragment([
            "message" => "Registration successful."
        ]);
    }


}
