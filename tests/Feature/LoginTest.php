<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_existing_user()
    {
        $user = User::create([
            'name' => 'alaa zamel',
            'email' => 'alaa.zamel80@gmail.com',
            'password' => bcrypt('secret')
        ]);

        # make a post request for endpoint
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'secret',
            'device_name' => 'iphone'
        ]);

        # this will create a token depending on whatever device_name
        $response->assertSuccessful();
        $this->assertNotEmpty($response->getContent());
        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'iphone',
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ]);
    }

    /** @test */
    public function get_user_from_token()
    {
        $user = User::create([
            'name' => 'alaa zamel',
            'email' => 'alaa.zamel80@gmail.com',
            'password' => bcrypt('secret')
        ]);
    }
}
