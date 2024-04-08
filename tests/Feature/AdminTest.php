<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertNotNull;

class AdminTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/admins', [
            'name' => 'marcell',
            'email' => 'marcell@gmail.com',
            'username' => 'marcell',
            'password' => 'rahasia',
            'role' => 'logistik',
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    'name' => 'marcell',
                    'email' => 'marcell@gmail.com',
                    'username' => 'marcell',
                    'role' => 'logistik',
                ]
            ]);
    }
    public function testRegisterFailed()
    {
        $this->post('/api/admins', [
            'name' => '',
            'email' => '',
            'username' => '',
            'password' => '',
            'role' => '',
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'email' => [
                        'The email field is required.'
                    ],
                    'username' => [
                        'The username field is required.'
                    ],
                    'password' => [
                        'The password field is required.'
                    ],
                    'role' => [
                        'The selected role is invalid.'
                    ],
                ]
            ]);
    }
    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/admins', [
            'name' => 'marcell',
            'email' => 'marcell@gmail.com',
            'username' => 'marcell',
            'password' => 'rahasia',
            'role' => 'logistik',
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        'username already registered'
                    ],
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([AdminSeeder::class]);
        $this->post('/api/admins/login', [
            'username' => 'test',
            'password' => 'test',
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test',
                ]
            ]);

        $admin = Admin::where('username', 'test')->first();
        assertNotNull($admin->token);
    }

    public function testLoginFailedUsernameNotFound()
    {
        $this->post('/api/admins/login', [
            'username' => 'test',
            'password' => 'test',
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'username or password wrong'
                    ]
                ]
            ]);
    }
    public function testLoginFailedPasswordWrong()
    {
        $this->seed([AdminSeeder::class]);
        $this->post('/api/admins/login', [
            'username' => 'test',
            'password' => 'salah',
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'username or password wrong'
                    ]
                ]
            ]);
    }

    public function testShowSuccess()
    {
        $this->seed([AdminSeeder::class]);
        $this->get('api/admins/current', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test',
                ]
            ]);
    }
    public function testShowUnauthorized()
    {
        $this->seed([AdminSeeder::class]);
        $this->get('api/admins/current',)->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }
    public function testShowInvalidToken()
    {
        $this->seed([AdminSeeder::class]);
        $this->get('api/admins/current', [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }
}
