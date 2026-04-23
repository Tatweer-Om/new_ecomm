<?php

namespace Modules\Color\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Color\Models\Color;
use Modules\Color\Policies\ColorPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Color::class => ColorPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
