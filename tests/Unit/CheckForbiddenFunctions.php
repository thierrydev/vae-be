<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Config;

use \Tests\CreatesApplication;

/**
 * Detects if debugging functions are present in the code
 * https://www.youtube.com/watch?v=_hFNsoa43fE
 * **/ 
test('expects dev functions to not be used')->expect(['end','dd','dump'])->not->toBeUsed();


