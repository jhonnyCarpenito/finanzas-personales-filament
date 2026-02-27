<?php

declare(strict_types=1);

namespace Tests\Feature\Infrastructure;

use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AppServiceProviderTest extends TestCase
{
    public function test_force_scheme_https_when_app_url_is_https(): void
    {
        Config::set('app.url', 'https://example.test');

        $provider = new AppServiceProvider($this->app);
        $provider->boot();

        $url = URL::to('/foo');

        $this->assertStringStartsWith('https://', $url);
    }
}

