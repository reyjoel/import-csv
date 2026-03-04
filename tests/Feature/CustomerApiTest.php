<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;

class CustomerApiTest extends TestCase
{
    /**
     * test customer
     */
    use RefreshDatabase;

    public function test_can_get_customers()
    {
        Customer::factory()->count(5)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'current_page',
                    'last_page',
                    'total'
                ])
                ->assertJsonCount(5, 'data');
    }
}
