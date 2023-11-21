<?php

namespace Tests\Feature;

use App\Models\User;
use Predis\Client;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testRegisterSuccessfulResponse(): void
    {
        $response = $this->post('/api/register', [
            "name" => "yasser",
            "email" => "yasser".time()."@gmail.com",
            "password" => "123123",
        ]);
        $response->assertStatus(200);
        $returnedData = json_decode($response->getContent());
        $this->assertObjectHasProperty("data", $returnedData);

        $returnedData = $returnedData->data;
        $this->assertObjectHasProperty("user", $returnedData);
    }

    public function testRegisterFailedResponse(): void
    {
        $faildResponse = $this->post('/api/register', [
            "name" => "",
            "email" => "yasser".time()."@gmail.com",
            "password" => "",
        ]);
        $returnedData = json_decode($faildResponse->getContent());

        $this->assertEquals($returnedData->message, 'The given data was invalid.');
        $this->assertObjectHasProperty('errors', $returnedData);
    }

    public function testLoginSuccessfulResponse(): void
    {
        $user = User::limit(1)->orderBy('id', 'desc')->get()->first();
        $response = $this->post('/api/login', [
            "email" => $user->email,
            "password" => '123123',
        ]);
        $returnedData = json_decode($response->getContent());
        $response->assertStatus(200);

        var_dump($returnedData);

        $returnedData = $returnedData->data;
        $this->assertObjectHasProperty("user", $returnedData);
        $this->assertObjectHasProperty("token", $returnedData);
    }

    public function testLoginFailedResponse(): void
    {
        $faildResponse = $this->post('/api/login', [
            "email" => "yasser1700236976@gmail.com",
            "password" => "",
        ]);

        $returnedArr = json_decode($faildResponse->getContent());
        $faildResponse->assertStatus(400);
    }

    public function testLogoutSuccessfulResponse(): void
    {
        $user = User::limit(1)->orderBy('id', 'desc')->get()->first();
        $response = $this->post('/api/login', [
            "email" => $user->email,
            "password" => "123123",
        ]);

        $returnedArr = json_decode($response->getContent());
        $returnedArr = $returnedArr->data;
        $token = $returnedArr->token;

        $response = $this->post('/api/logout', [], [
            'authorization' => $token,
        ]);
        $returnedArr = json_decode($response->getContent());
        $returnedArr = $returnedArr->data;

        $response->assertStatus(200);
        $this->assertObjectHasProperty("message", $returnedArr);
        $this->assertEquals($returnedArr->message, "logged out");
    }

}
