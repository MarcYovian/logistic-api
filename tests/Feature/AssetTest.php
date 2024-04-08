<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Asset;
use Database\Seeders\AdminSeeder;
use Database\Seeders\AssetSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetTest extends TestCase
{
    public function testStoreSuccess()
    {
        $this->seed([AdminSeeder::class]);

        $this->post('/api/assets', [
            'name' => 'test',
            'type' => 'test',
            'description' => '',
            'image_Path' => '',
        ], [
            'Authorization' => 'test',
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'test',
                    'type' => 'test',
                ]
            ]);
    }
    public function testStoreFailed()
    {
        $this->seed([AdminSeeder::class]);

        $this->post('/api/assets', [
            'name' => '',
            'type' => '',
            'description' => '',
            'image_Path' => '',
        ], [
            'Authorization' => 'test',
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'type' => [
                        'The type field is required.'
                    ],
                ]
            ]);
    }
    public function testStoreUnauthorized()
    {
        $this->seed([AdminSeeder::class]);

        $this->post('/api/assets', [
            'name' => '',
            'type' => '',
            'description' => '',
            'image_Path' => '',
        ], [
            'Authorization' => 'salah',
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ],
                ]
            ]);
    }

    public function testShowSuccess()
    {
        $this->seed([AdminSeeder::class, AssetSeeder::class]);

        $asset = Asset::query()->limit(1)->first();

        $this->get('/api/assets/' . $asset->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test',
                    'type' => 'test',
                    'description' => 'test',
                    'image_Path' => 'test',
                ]
            ]);
    }
    public function testShowNotFound()
    {
        $this->seed([AdminSeeder::class, AssetSeeder::class]);

        $asset = Asset::query()->limit(1)->first();

        $this->get('/api/assets/' . $asset->id + 1, [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([AdminSeeder::class, AssetSeeder::class]);

        $asset = Asset::query()->limit(1)->first();

        $this->put('/api/assets/' . $asset->id, [
            'name' => 'test2',
            'type' => 'test2',
            'description' => 'test2',
            'image_Path' => 'test2',
        ], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test2',
                    'type' => 'test2',
                    'description' => 'test2',
                    'image_Path' => 'test2',
                ]
            ]);
    }
    public function testUpdatefailed()
    {
        $this->seed([AdminSeeder::class, AssetSeeder::class]);

        $asset = Asset::query()->limit(1)->first();

        $this->put('/api/assets/' . $asset->id, [
            'name' => '',
            'type' => 'test2',
            'description' => 'test2',
            'image_Path' => 'test2',
        ], [
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                ]
            ]);
    }
    public function testUpdateValidationError()
    {
        $this->seed([AdminSeeder::class, AssetSeeder::class]);

        $asset = Asset::query()->limit(1)->first();

        $this->put('/api/assets/' . $asset->id, [
            'name' => '',
            'type' => 'test2',
            'description' => 'test2',
            'image_Path' => 'test2',
        ], [
            'Authorization' => 'test1'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ],
                ]
            ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([AdminSeeder::class, AssetSeeder::class]);

        $asset = Asset::query()->limit(1)->first();

        $this->delete('/api/assets/' . $asset->id, [], [
            "Authorization" => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }
    public function testDeleteNotFound()
    {
        $this->seed([AdminSeeder::class, AssetSeeder::class]);

        $asset = Asset::query()->limit(1)->first();

        $this->delete('/api/assets/' . $asset->id + 1, [], [
            "Authorization" => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }
    public function testDeleteValidationError()
    {
        $this->seed([AdminSeeder::class, AssetSeeder::class]);

        $asset = Asset::query()->limit(1)->first();

        $this->delete('/api/assets/' . $asset->id, [], [
            "Authorization" => 'test1'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ]);
    }
}
