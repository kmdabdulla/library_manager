<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserActionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public static $validUserForCheckIn = NULL;

    public function test_perform_user_action_unauthorized_access() {
        $this->json('POST', '/api/useraction', ['Accept' => 'application/json'])
            ->assertStatus(401);
    }

    public function test_perform_user_action_no_input() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $this->json('POST', '/api/useraction', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "bookId" => ["The bookId field is required."],
                "action" => ["The action field is required."],
            ]
        ]);
    }

    public function test_perform_user_action_invalid_book_id() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $requestData = [
            "bookId" => "abd1",
            "action" => "CHECKIN"
        ];
        $this->json('POST', '/api/useraction',$requestData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "bookId" => ["The book id must be a number."],
            ]
        ]);
    }

    public function test_perform_user_action_invalid_action_value() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $requestData = [
            "bookId" => "1",
            "action" => "CHECK"
        ];
        $this->json('POST', '/api/useraction',$requestData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
            "message" => "Action should be CHECKIN or CHECKOUT."
        ]);
    }

    public function test_perform_user_action_valid_checkout() {
        UserActionTest::$validUserForCheckIn = User::factory()->create();
        $this->actingAs(UserActionTest::$validUserForCheckIn, 'api');
        $requestData = [
            "bookId" => "1",
            "action" => "CHECKOUT"
        ];
        $this->json('POST', '/api/useraction',$requestData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
            "message" => "Book checked out successfully."
        ]);
    }

    public function test_perform_user_action_invalid_checkout() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $requestData = [
            "bookId" => "1",
            "action" => "CHECKOUT"
        ];
        $this->json('POST', '/api/useraction',$requestData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
            "message" => "Book cannot be checked out."
        ]);
    }

    public function test_perform_user_action_invalid_checkin_operation() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $requestData = [
            "bookId" => "1",
            "action" => "CHECKIN"
        ];
        $this->json('POST', '/api/useraction',$requestData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
            "message" => "Not Permitted to check in the book."
        ]);
    }

    public function test_perform_user_action_valid_checkin_operation() {
        $this->actingAs(UserActionTest::$validUserForCheckIn, 'api');
        $requestData = [
            "bookId" => "1",
            "action" => "CHECKIN"
        ];
        $this->json('POST', '/api/useraction',$requestData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
            "message" => "Book checked in successfully."
        ]);
    }
}
