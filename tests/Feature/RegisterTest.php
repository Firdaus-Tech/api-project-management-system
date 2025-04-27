<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->password = 'Password123@';
});

describe('User Registration', function () {
    it('registers a user successfully', function () {
        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ]);

        // Check for successful response
        $response->assertStatus(200); // HTTP status code for created

        // Assert that the user is created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);

        // Optional: Assert that the password is hashed
        $user = User::where('email', 'testuser@example.com')->first();
        $this->assertTrue(Hash::check($this->password, $user->password));
    });

    it('fails when the email is already taken', function () {
        // Create an existing user
        User::create([
            'name' => 'Existing User',
            'email' => 'existinguser@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Try to register with the same email
        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'existinguser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Assert that it returns a validation error for the email
        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors(['email']);
    });

    it('validates the registration form data', function () {
        // Test for missing name
        $response = $this->postJson(route('register'), [
            'email' => 'testuser@example.com',
            'password' => 'Password123@',
            'password_confirmation' => 'Password123@',
        ]);
        $response->assertStatus(422);  // Unprocessable Entity
        $response->assertJsonValidationErrors(['name']);  // Check if the name is missing

        // Test for invalid email format
        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'Password123@',
            'password_confirmation' => 'Password123@',
        ]);
        $response->assertStatus(422);  // Unprocessable Entity
        $response->assertJsonValidationErrors(['email']);  // Check if the email is invalid

        // Test for password confirmation mismatch
        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'Password123@',
            'password_confirmation' => 'Password123',  // Passwords do not match
        ]);
        $response->assertStatus(422);  // Unprocessable Entity
        $response->assertJsonValidationErrors(['password']);  // Check if password confirmation fails

        // Test for password length
        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'short',  // Password too short
            'password_confirmation' => 'short',
        ]);
        $response->assertStatus(422);  // Unprocessable Entity
        $response->assertJsonValidationErrors(['password']);  // Check if password length is validated
    });
});
