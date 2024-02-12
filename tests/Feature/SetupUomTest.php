<?php

namespace Tests\Feature;

use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetupUomTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testIndex()
    {
        // Create some sample data
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('size.index'));
        $response->assertSuccessful();
        $response->assertSee('#size-table');
    }
    public function testCreate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('size.create'));
        $response->assertSuccessful();
        $response->assertSee('Create UOM');
    }
    public function testStore_working()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $data = [
            'name' => 'New Size',
            'description' => 'This is a new size',
            'status' => 1,
        ];
        $response = $this->post(route('size.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('sizes', $data);
    }
    public function testStore_failing()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);
        $data = [
            'name' => '',
            'description' => 'This is a new size',
            'status' => 1,
        ];
        // Send a POST request to the store route without any data
        $response = $this->post(route('size.store'));

        // Assert that the response is a redirect with a validation error
        $response->assertRedirect();
        $response->assertSessionHasErrors(['name']);
    }
    public function testEdit()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);
        // Create a sample size instance
        $size = new Size();
        $size->name = 'New Size';
        $size->description = 'This is a new size';
        $size->status = 1;
        $size->save();
        // Send a GET request to the edit route with the size ID
        $response = $this->get(route('size.edit', $size->id));

        // Assert that the response is successful
        $response->assertSuccessful();

        // Assert that the view contains the size data
        $response->assertSee($size->name);
        $response->assertSee($size->description);
    }
    public function testUpdate_working()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a sample size instance
        $size = new Size();
        $size->name = 'Original Size';
        $size->description = 'This is the original size';
        $size->status = 1;
        $size->save();

        // Define the data for the updated size
        $data = [
            'name' => 'Updated Size',
            'description' => 'This is an updated size',
            'status' => 1,
        ];

        // Send a PUT request to the update route with the size ID and updated data
        $response = $this->put(route('size.update', $size->id), $data);

        // Assert that the response is a redirect
        $response->assertRedirect();
        $this->assertDatabaseHas('sizes', $data);

        // Refresh the size instance from the database
        $size->refresh();

        // Assert that the size data has been updated
        $this->assertEquals($data['name'], $size->name);
        $this->assertEquals($data['description'], $size->description);
    }
    public function testUpdate_failing()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a sample size instance
        $size = Size::factory()->create();

        // Define invalid data for the updated size
        $data = [
            'name' => '', // Invalid: empty name
            'description' => 'This is an updated size',
        ];

        // Send a PUT request to the update route with the size ID and invalid data
        $response = $this->put(route('size.update', $size->id), $data);

        // Assert that the response is a redirect with validation errors
        $response->assertRedirect();
        $response->assertSessionHasErrors(['name']);

        // Reload the size instance from the database
        $size->refresh();

        // Assert that the size data has not been updated
        $this->assertNotEquals($data['name'], $size->name);
        $this->assertNotEquals($data['description'], $size->description);
    }
}
