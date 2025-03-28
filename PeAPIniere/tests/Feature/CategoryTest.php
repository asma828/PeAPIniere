<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Repositories\Interfaces\CategoryDAOInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryDAO;
    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin_' . uniqid() . '@example.com',
            'role' => 'admin',
            'password' => bcrypt('password')
        ]);

        $this->token = auth('api')->login($this->user);

        $this->categoryDAO = Mockery::mock(CategoryDAOInterface::class);
        
        $this->app->instance(CategoryDAOInterface::class, $this->categoryDAO);
    }

    public function test_index()
    {
        $mockCategories = [
            ['id' => 1, 'name' => 'Category 1'],
            ['id' => 2, 'name' => 'Category 2']
        ];

        $this->categoryDAO
            ->shouldReceive('getAllCategories')
            ->once()
            ->andReturn($mockCategories);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('categories.index'));

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                ['id' => 1, 'name' => 'Category 1'],
                ['id' => 2, 'name' => 'Category 2']
            ]);
    }

    public function test_indexx()
    {
        $this->categoryDAO
            ->shouldReceive('getAllCategories')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('categories.index'));

        $response->assertStatus(500)
            ->assertJsonStructure([
                'error',
                'message'
            ]);
    }

    public function test_store()
    {
        $categoryData = ['name' => 'New Category'];

        $this->categoryDAO
            ->shouldReceive('createCategory')
            ->once()
            ->with($categoryData)
            ->andReturn(['id' => 1, ...$categoryData]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('categories.store'), $categoryData);

        $response->assertStatus(201)
            ->assertJson([
                'id' => 1,
                'name' => 'New Category'
            ]);
    }


    public function test_show()
    {
        $mockCategory = ['id' => 1, 'name' => 'Existing Category'];

        $this->categoryDAO
            ->shouldReceive('getCategoryById')
            ->once()
            ->with(1)
            ->andReturn($mockCategory);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('categories.show', 1));

        $response->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'name' => 'Existing Category'
            ]);
    }

    public function test_update()
    {
        $existingCategory = Category::factory()->create(['name' => 'Old Name']);
        $updateData = ['name' => 'Updated Category Name'];

        $this->categoryDAO
            ->shouldReceive('updateCategory')
            ->once()
            ->andReturn(array_merge($existingCategory->toArray(), $updateData));

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson(route('categories.update', $existingCategory), $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Category Name'
            ]);
    }
    public function test_destroy()
    {
        $existingCategory = Category::factory()->create();

        $this->categoryDAO
            ->shouldReceive('deleteCategory')
            ->once()
            ->andThrow(new \Exception('Delete failed'));

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson(route('categories.destroy', $existingCategory));

        $response->assertStatus(500)
            ->assertJsonStructure([
                'error',
                'message'
            ]);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}