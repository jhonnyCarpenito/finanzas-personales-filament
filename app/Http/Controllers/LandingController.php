<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

final class LandingController extends Controller
{
    public function __invoke(Request $request): View
    {
        if ($request->routeIs('landing.en')) {
            App::setLocale('en');
        } else {
            App::setLocale('es');
        }

        return view('landing.index');
    }
}
