<?php

namespace Ja\Tall\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    private string $path = __DIR__ . '/../..';

    public function register()
    {
        $this
            ->registerClassAliases();
    }

    public function boot()
    {
        $this
            ->loadRoutes()
            ->loadTranslations()
            ->loadViews()
            ->loadComponents();
    }

    private function loadRoutes()
    {
        $this->loadRoutesFrom(
            "{$this->path}/routes/routes.php"
        );

        return $this;
    }

    private function loadTranslations()
    {
        $this->loadTranslationsFrom(
            "{$this->path}/lang",
            'tall'
        );

        return $this;
    }

    private function loadComponents(): self
    {
        Blade::componentNamespace(
            'Ja\\Tall\\Blade',
            'tall'
        );

        return $this;
    }

    private function loadViews(): self
    {
        $this->loadViewsFrom(
            "{$this->path}/resources/views",
            'tall'
        );

        return $this;
    }

    public function registerClassAliases(): self
    {
        $this->app->booting(function ($app) {
            $loader = AliasLoader::getInstance();

            collect([
                'Tall' => 'Ja\\Tall\\Support\\Helper',
            ])->map(fn ($class, $namespace) => (
                $loader->alias(
                    $namespace,
                    $class
                )
            ));
        });

        return $this;
    }
}
