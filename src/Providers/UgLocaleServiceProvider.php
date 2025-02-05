<?php

namespace Intanode\UgLocale\Providers;

use Illuminate\Support\ServiceProvider;
use Intanode\UgLocale\Services\LocaleService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UgLocaleServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		$this->registerMigrations();
		$this->publishConfig();
		$this->publishMigrations();
		$this->publishRoutes();
		// $this->afterPublishing();
		$this->registerRoutes();
	}

	public function register(): void
	{
		$this->app->singleton(LocaleService::class, fn () => new LocaleService());
		
		$this->mergeConfigFrom(__DIR__ . '/../config/uglocale.php', 'uglocale');

		if ($this->app->runningInConsole()) {
			$this->registerCommands();
		}
	}

	private function registerRoutes(): void
	{
		$this->loadRoutesFrom(__DIR__ . '/../routes/ugLocaleRoutes.php');
	}

	private function registerMigrations(): void
	{
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
	}

	private function publishConfig(): void
	{
		$this->publishes([
			__DIR__ . '/../config/uglocale.php' => config_path('uglocale.php'),
		], 'ug-locale-config');
	}

	private function publishMigrations(): void
	{
		$this->publishes([
			__DIR__ . '/../database/migrations' => database_path('migrations'),
		], 'ug-locale-migrations');
	}

	private function publishRoutes(): void
	{
		$this->publishes([
			__DIR__ . '/../routes/ugLocaleRoutes.php' => base_path('routes/ugLocaleRoutes.php'),
		], 'ug-locale-routes');
	}

	private function afterPublishing()
	{
       // Path to web.php
		$webRoutesPath = base_path('routes/web.php');
		$requireStatement = "require __DIR__.'/ugLocaleRoutes.php';";

		if (File::exists($webRoutesPath)) {
			$content = File::get($webRoutesPath);

           // Check if it's already added to avoid duplication
			if (!str_contains($content, $requireStatement)) {
				File::append($webRoutesPath, PHP_EOL . $requireStatement . PHP_EOL);
			}
		}
	}

	private function registerCommands(): void
	{
		$this->commands([
			\Intanode\UgLocale\Console\TransformAndSeedLocale::class,
		]);
	}
}
