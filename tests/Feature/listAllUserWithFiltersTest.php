<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class listAllUserWithFiltersTest extends TestCase
{
    public function testListUsers(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/v1/users');
        $response->assertStatus(200);
        $this->assertEquals(10, count(json_decode($response->getContent())));
    }

    public function testListUsersFilterByBalance(): void
    {
        $response = $this->get('/api/v1/users?balanceMin=200&balanceMax=300');
        $response->assertStatus(200);
        $this->assertEquals(6, count(json_decode($response->getContent())));
    }

    public function testListUsersFilterByCurrency(): void
    {
        $response = $this->get('/api/v1/users?currency=USD');
        $response->assertStatus(200);
        $this->assertEquals(2, count(json_decode($response->getContent())));
    }

    public function testListUsersFilterByStatus(): void
    {
        $response = $this->get('/api/v1/users?statusCode=decline');
        $response->assertStatus(200);
        $this->assertEquals(3, count(json_decode($response->getContent())));
    }

    public function testListUsersApplyAllFilters(): void
    {
        $response = $this->get('/api/v1/users?statusCode=decline&balanceMin=100&balanceMax=300');
        $response->assertStatus(200);
        $this->assertEquals(3, count(json_decode($response->getContent())));
    }
}
