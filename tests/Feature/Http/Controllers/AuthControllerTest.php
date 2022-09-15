<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequireEmailLogin()
    {
        $this->json('POST', 'api/auth/login')
            ->assertStatus(422)                
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    public function testUserLoginUnauthorised()
    {
        $user = ['email' => 'customer@gamail.com', 'password' => 'dummydummy'];
        $this->json('POST', 'api/auth/login', $user)
            ->assertStatus(401)                
            ->assertJson([
                'message' => 'Unauthorised',
                // 'errors' => []
            ]);
    }

    public function testUserLoginSuccessfully()
    {
        $user = ['email' => 'customer@gmail.com', 'password' => 'dummydummy'];
        $this->json('POST', 'api/auth/login', $user)
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'message'
            ]);
    }

    public function testLogoutSuccessfully()
    {
        $user = ['email' => 'customer@gmail.com', 'password' => 'dummydummy'];

        Auth::attempt($user);
        $token = Auth::user()->createToken('AuthApp')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('GET', 'api/auth/logout', [], $headers)
            ->assertStatus(200);
    }
}
