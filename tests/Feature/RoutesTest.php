<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RoutesTest extends TestCase
{

  use WithoutMiddleware;

  /**  @test */
  public function test_form_login_route()
  {
    $response = $this->get('/login');
    $response->assertStatus(200);
  }

}