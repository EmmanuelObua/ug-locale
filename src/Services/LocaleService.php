<?php

namespace Intanode\UgLocale\Services;

use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Exception;

class LocaleService
{
	/**
	 * Load and transform region data from JSON.
	 */
	public function getTransformedRegions(): array
	{
		return $this->loadJsonAndTransform('regions.json', fn($region) => [
			'id' => (int) $region['id'],
			'name' => ucwords(strtolower($region['name'])),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]);
	}

	/**
	 * Load and transform district data from JSON.
	 */
	public function getTransformedDistricts(): array
	{
		$districts = $this->loadJson('districts.json');
		$regionMappings = $this->loadJson('districtRegions.json');

		$regionMap = collect($regionMappings)->pluck('region_id', 'name')->mapWithKeys(fn($regionId, $name) => [strtoupper($name) => $regionId])->toArray();

		return array_map(fn($district) => [
			'id' => (int) $district['id'],
			'name' => ucwords(strtolower($district['name'])),
			'region_id' => $regionMap[strtoupper($district['name'])] ?? null,
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		], $districts);
	}

	/**
	 * Load and transform county data from JSON.
	 */
	public function getTransformedCounties(): array
	{
		return $this->loadJsonAndTransform('counties.json', fn($county) => [
			'id' => (int) $county['id'],
			'name' => ucwords(strtolower($county['name'])),
			'district_id' => (int) $county['district'],
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]);
	}

	/**
	 * Load and transform sub-county data from JSON.
	 */
	public function getTransformedSubCounties(): array
	{
		return $this->loadJsonAndTransform('subcounties.json', fn($subcounty) => [
			'id' => (int) $subcounty['id'],
			'name' => ucwords(strtolower($subcounty['name'])),
			'county_id' => (int) $subcounty['county'],
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]);
	}

	/**
	 * Load and transform parish data from JSON.
	 */
	public function getTransformedParishes(): array
	{
		return $this->loadJsonAndTransform('parishes.json', fn($parish) => [
			'id' => (int) $parish['id'],
			'name' => ucwords(strtolower($parish['name'])),
			'sub_county_id' => (int) $parish['subcounty'],
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]);
	}

	/**
	 * Load and transform village data from JSON.
	 */
	public function getTransformedVillages(): array
	{
		return $this->loadJsonAndTransform('villages.json', fn($village) => [
			'id' => (int) $village['id'],
			'name' => ucwords(strtolower($village['name'])),
			'parish_id' => (int) $village['parish'],
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]);
	}

	/**
	 * Load JSON data and apply a transformation function.
	 */
	private function loadJsonAndTransform(string $filename, callable $transformer): array
	{
		return array_map($transformer, $this->loadJson($filename));
	}

	/**
	 * Load JSON data from the specified file.
	 */
	private function loadJson(string $filename): array
	{
		$filePath = __DIR__ . '/../database/seeders/' . $filename;
		
		if (!File::exists($filePath)) {
			throw new Exception("File {$filename} not found.");
		}

		return json_decode(File::get($filePath), true) ?? [];
	}
}
