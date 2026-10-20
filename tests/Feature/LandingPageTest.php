<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_landing_page_es_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('lang="es"', false);
        $response->assertSee(__('landing.hero.title'));
        $response->assertSee('Iniciar sesión');
        $response->assertSee('href="' . route('landing.en') . '"', false);
    }

    public function test_landing_page_en_is_accessible(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
        $response->assertSee('lang="en"', false);
        $response->assertSee(__('landing.hero.title', [], 'en'));
        $response->assertSee('Log in');
        $response->assertSee('href="' . route('landing') . '"', false);
    }

    public function test_landing_page_shows_dashboard_link_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Ir al panel');
        $response->assertDontSee('Iniciar sesión');
        $response->assertSee('href="/app"', false);
    }

    public function test_landing_page_contains_socialite_redirect(): void
    {
        $response = $this->get('/');
        
        $response->assertSee('/auth/google/redirect', false);
    }
}
