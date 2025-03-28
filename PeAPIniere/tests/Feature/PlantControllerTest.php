<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Plant;
use App\Models\User;
use App\Repositories\Interfaces\PlantDAOInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PlantControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $plantDAO;
    protected $adminUser;
    protected $clientUser;
    protected $adminToken;
    protected $clientToken;
    protected $category;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'email' => 'admin_' . uniqid() . '@example.com',
            'role' => 'admin',
            'password' => bcrypt('password')
        ]);

        $this->clientUser = User::factory()->create([
            'email' => 'client_' . uniqid() . '@example.com',
            'role' => 'client',
            'password' => bcrypt('password')
        ]);

        $this->adminToken = auth('api')->login($this->adminUser);
        $this->clientToken = auth('api')->login($this->clientUser);

        $this->category = Category::factory()->create();

        $this->plantDAO = Mockery::mock(PlantDAOInterface::class);
        $this->app->instance(PlantDAOInterface::class, $this->plantDAO);
    }

    public function test_index()
    {
        $mockPlants = [
            ['id' => 1, 'name' => 'Plant 1', 'slug' => 'plant-1', 'price' => 10.99, 'category_id' => $this->category->id],
            ['id' => 2, 'name' => 'Plant 2', 'slug' => 'plant-2', 'price' => 15.99, 'category_id' => $this->category->id]
        ];
        $this->plantDAO
            ->shouldReceive('getAllPlants')
            ->once()
            ->andReturn($mockPlants);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->clientToken)
            ->getJson('/api/plants');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                ['id' => 1, 'name' => 'Plant 1', 'slug' => 'plant-1', 'price' => 10.99, 'category_id' => $this->category->id],
                ['id' => 2, 'name' => 'Plant 2', 'slug' => 'plant-2', 'price' => 15.99, 'category_id' => $this->category->id]
            ]);
    }

    public function test_store()
    {
        $plantData = [
            'name' => 'New Plant',
            'slug' => 'new-plant',
            'description' => 'A beautiful new plant',
            'price' => 19.99,
            'category_id' => $this->category->id
        ];

        $this->plantDAO
            ->shouldReceive('createPlant')
            ->once()
            ->with($plantData)
            ->andReturn(['id' => 1, ...$plantData]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson('/api/plants', $plantData);

        $response->assertStatus(201)
            ->assertJson([
                'id' => 1,
                'name' => 'New Plant',
                'slug' => 'new-plant',
                'description' => 'A beautiful new plant',
                'price' => 19.99,
                'category_id' => $this->category->id
            ]);
    }

    public function test_show()
    {
        $mockPlant = [
            'id' => 1, 
            'name' => 'Existing Plant', 
            'slug' => 'existing-plant',
            'price' => 12.99,
            'category_id' => $this->category->id
        ];

        $this->plantDAO
            ->shouldReceive('getPlantBySlug')
            ->once()
            ->with('existing-plant')
            ->andReturn($mockPlant);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->clientToken)
            ->getJson('/api/plants/existing-plant');

        $response->assertStatus(200)
            ->assertJson([
                'id' => 1, 
                'name' => 'Existing Plant', 
                'slug' => 'existing-plant',
                'price' => 12.99,
                'category_id' => $this->category->id
            ]);
    }

    public function test_update()
    {
        $existingPlant = Plant::factory()->create([
            'name' => 'Old Name',
            'description' => 'Test plant description',
            'price' => 19.99,
            'category_id' => $this->category->id
        ]);
    
        $updateData = [
            'name' => 'Updated Plant Name',
            'price' => 25.99
        ];
    
        $this->plantDAO
            ->shouldReceive('updatePlant')
            ->once()
            ->andReturn(array_merge($existingPlant->toArray(), $updateData));
    
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->putJson("/api/plants/{$existingPlant->slug}", $updateData);
    
        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Plant Name',
                'price' => 25.99
            ]);
    }
    
    public function test_destroy()
    {
        $existingPlant = Plant::factory()->create([
            'description' => 'Test plant description',
            'price' => 24.99,
            'category_id' => $this->category->id
        ]);
    
        $this->plantDAO
            ->shouldReceive('deletePlant')
            ->once()
            ->andReturn(true);
    
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("/api/plants/{$existingPlant->slug}");
    
        $response->assertStatus(200);
        $this->assertTrue($response->json());
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}