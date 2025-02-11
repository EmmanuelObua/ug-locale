<?php

namespace Intanode\UgLocale\Console;

use Illuminate\Console\Command;
use Intanode\UgLocale\Services\LocaleService;
use Intanode\UgLocale\Models\{District, County, SubCounty, Parish, Village, Region};
use Illuminate\Support\Facades\{Artisan, DB, Schema, Log};

class TransformAndSeedLocale extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'locale:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Transform Uganda locale JSON data and seed it into the database';

	/**
	 * Constructor for dependency injection.
	 */
	public function __construct(private readonly LocaleService $localeService)
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 */
	public function handle(): void
	{
		try {
			$this->runMigrationsIfNeeded();

			$batchSize = config('uglocale.batch_size', 1000); // Default to 1000 if not set

			$this->newLine(); // Adds an empty line for spacing
			$this->info(str_repeat('=', 50)); // Adds a separator
			$this->seedTable('Regions', Region::class, fn() => $this->localeService->getTransformedRegions());

			$this->newLine();
			$this->info(str_repeat('=', 50));
			$this->seedTable('Districts', District::class, fn() => $this->localeService->getTransformedDistricts());

			$this->newLine();
			$this->info(str_repeat('=', 50));
			$this->seedTable('Counties', County::class, fn() => $this->localeService->getTransformedCounties());

			$this->newLine();
			$this->info(str_repeat('=', 50));
			$this->seedTable('Sub-Counties', SubCounty::class, fn() => $this->localeService->getTransformedSubCounties());

			$this->newLine();
			$this->info(str_repeat('=', 50));
			$this->seedTable('Parishes', Parish::class, fn() => $this->localeService->getTransformedParishes(), $batchSize);

			$this->newLine();
			$this->info(str_repeat('=', 50));
			$this->seedTable('Villages', Village::class, fn() => $this->localeService->getTransformedVillages(), $batchSize);

			$this->newLine();
			$this->info('🎉 All data has been successfully seeded!');
		} catch (\Throwable $e) {
			Log::error('Locale seeding error', ['exception' => $e]);
			$this->error('❌ An error occurred: ' . $e->getMessage());
		}
	}

	/**
	 * Run migrations only if they haven't been executed.
	 */
	private function runMigrationsIfNeeded(): void
	{
		if (!Schema::hasTable('regions')) {
			$this->info('🚀 Running migrations...');
			if (Artisan::call('migrate', ['--force' => true]) === 0) {
				$this->info('✅ Migrations ran successfully.');
			} else {
				throw new \RuntimeException('❌ Failed to run migrations.');
			}
		} else {
			$this->info('⚡ Migrations already up to date. Skipping...');
		}
	}

	/**
	 * Seed a given table with transformed data and show progress.
	 *
	 * @param string   $name       Table name for display.
	 * @param string   $modelClass Model class.
	 * @param callable $transform  Callback to get transformed data.
	 * @param int|null $batchSize  Optional batch size for large inserts.
	 */
	private function seedTable(string $name, string $modelClass, callable $transform, ?int $batchSize = null): void
	{
		$this->info("📦 Transforming and seeding $name...");

		$data = $transform();
		if (empty($data)) {
			$this->warn("⚠️ $name has no data to insert.");
			return;
		}

		$totalRecords = count($data);
		$this->info("📊 Total $name records: $totalRecords");

		DB::transaction(function () use ($modelClass, $data, $batchSize, $name, $totalRecords) {

			$driver = Schema::getConnection()->getDriverName();

			$table = (new $modelClass)->getTable();

			if ($driver === 'sqlsrv') {
			    DB::unprepared("SET IDENTITY_INSERT {$table} ON");
			}


			if ($batchSize) {
				$chunks = array_chunk($data, $batchSize);
				$this->output->progressStart(count($chunks));

				foreach ($chunks as $chunk) {
					$modelClass::insert($chunk);
					$this->output->progressAdvance();
				}

				$this->output->progressFinish();
			} else {
				$this->output->progressStart($totalRecords);
				foreach ($data as $record) {
					$modelClass::insert([$record]);
					$this->output->progressAdvance();
				}
				$this->output->progressFinish();
			}

			if ($driver === 'sqlsrv') {
			    DB::unprepared("SET IDENTITY_INSERT {$table} OFF");
			}

		});

		$this->info("✅ $name have been successfully seeded!");
	}
}
