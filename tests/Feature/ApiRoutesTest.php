<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ApiRoutesTest extends TestCase
{
    public function test_core_api_routes_are_registered(): void
    {
        $routes = [
            'users.index',
            'departements.index',
            'formations.index',
            'ecs.index',
            'surveillants.index',
            'salles.index',
            'cameras.index',
            'evaluations.index',
            'videos.index',
        ];

        foreach ($routes as $route) {
            $this->assertTrue(Route::has($route), "Route [{$route}] is not registered.");
        }
    }
}
