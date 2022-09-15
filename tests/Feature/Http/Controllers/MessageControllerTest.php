<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class MessageControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGuestSendMessageUnauthorised()
    {
        // $headers = [];
       
        $this->json('POST', 'api/message')
        ->assertStatus(401)                
        ->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testRequireRecipientMessage()
    {
        $user = ['email' => 'customer@gmail.com', 'password' => 'dummydummy'];

        Auth::attempt($user);
        $token = Auth::user()->createToken('AuthApp')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];
       
        $this->json('POST', 'api/message', [], $headers)
            ->assertStatus(422)                
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'recipient_id' => ['The recipient id field is required.'],
                    'message' => ['The message field is required.']
                ]
            ]);
    }
    
    public function testRecipientMessageSuccessfully()
    {
        $user = ['email' => 'customer@gmail.com', 'password' => 'dummydummy'];
        $message = ['recipient_id' => '1', 'message' => 'tes123'];
        Auth::attempt($user);
        $token = Auth::user()->createToken('AuthApp')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];
       
        $this->json('POST', 'api/message',  $message, $headers)
            ->assertStatus(200)                
            ->assertJson([
                'message' => 'Data saved successfully',
            ]);
    }
}
