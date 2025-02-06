<?php

namespace Intanode\UgLocale\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Intanode\UgLocale\Models\{District, County, SubCounty, Parish, Village, Region};
use Dedoc\Scramble\Attributes\QueryParameter;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class UgLocaleController
 * Handles CRUD operations for Ugandan administrative divisions.
 */
class UgLocaleController extends Controller
{
	
	/**
	 * Retrieve all regions.
	 * 
	 * @response array{status: bool, data: AnonymousResourceCollection[]}
	 *  
	 */
	public function getRegions(): JsonResponse
	{
		$regions = Region::all();

		return response()->json([
			'status' => true,
			'data'   => $regions,
		]);
	}

	/**
	 * Retrieve all districts within a specified region.
	 * 
	 * Expects a query parameter:
	 * - region_id: int
	 * 
	 * @param Request $request
	 * @response array{status: bool, data: AnonymousResourceCollection[]}
	 */

	#[QueryParameter(
		'region_id', 
		description: 'Region Id the district belongs to.', 
		type: 'int', 
		required: true,
		example:1
	)]

	public function getDistrictsByRegion(Request $request): JsonResponse
	{

		$request->validate([
			/** @query */
			'region_id' => ['required', 'integer'],
		]);

		$regionId = (int) $request->input('region_id');

		$districts = District::where('region_id', $regionId)->get();

		return response()->json([
			'status' => true,
			'data'   => $districts,
		]);
	}

	/**
	 * Retrieve all counties within a specified district.
	 *
	 * Expects a query parameter:
	 * - district_id: int
	 *
	 * @param Request $request
	 * @response array{status: bool, data: AnonymousResourceCollection[]}
	 */

	#[QueryParameter(
		'district_id', 
		description: 'District Id the county belongs to.', 
		type: 'int', 
		required: true,
		example:32
	)]

	public function getCountiesByDistrict(Request $request): JsonResponse
	{

		$request->validate([
			/** @query */
			'district_id' => ['required', 'integer'],
		]);

		$districtId = (int) $request->input('district_id');

		$counties = County::where('district_id', $districtId)->get();

		return response()->json([
			'status' => true,
			'data'   => $counties,
		]);
	}

	/**
	 * Retrieve all sub-counties within a specified county.
	 *
	 * Expects a query parameter:
	 * - county_id: int
	 *
	 * @param Request $request
	 * @response array{status: bool, data: AnonymousResourceCollection[]}
	 */

	#[QueryParameter(
		'county_id', 
		description: 'County Id the sub county belongs to.', 
		type: 'int', 
		required: true,
		example:69
	)]

	public function getSubCountiesByCounty(Request $request): JsonResponse
	{

		$request->validate([
			/** @query */
			'county_id' => ['required', 'integer'],
		]);

		$countyId = (int) $request->input('county_id');

		$subCounties = SubCounty::where('county_id', $countyId)->get();

		return response()->json([
			'status' => true,
			'data'   => $subCounties,
		]);
	}

	/**
	 * Retrieve all parishes within a specified sub-county.
	 *
	 * Expects a query parameter:
	 * - sub_county_id: int
	 *
	 * @param Request $request
	 * @response array{status: bool, data: AnonymousResourceCollection[]}
	 */

	#[QueryParameter(
		'sub_county_id', 
		description: 'Sub County Id the parish belongs to.', 
		type: 'int', 
		required: true,
		example:453
	)]

	public function getParishesBySubCounty(Request $request): JsonResponse
	{

		$request->validate([
			/** @query */
			'sub_county_id' => ['required', 'integer'],
		]);

		$subCountyId = (int) $request->input('sub_county_id');

		$parishes = Parish::where('sub_county_id', $subCountyId)->get();

		return response()->json([
			'status' => true,
			'data'   => $parishes,
		]);
	}

	/**
	 * Retrieve all villages within a specified parish.
	 *
	 * Expects a query parameter:
	 * - parish_id: int
	 *
	 * @param Request $request
	 * @response array{status: bool, data: AnonymousResourceCollection[]}
	 */

	#[QueryParameter(
		'parish_id', 
		description: 'Parish Id the village belongs to.', 
		type: 'int', 
		required: true,
		example:2040
	)]

	public function getVillagesByParish(Request $request): JsonResponse
	{

		$request->validate([
			/** @query */
			'parish_id' => ['required', 'integer'],
		]);

		$parishId = (int) $request->input('parish_id');

		$villages = Village::where('parish_id', $parishId)->get();

		return response()->json([
			'status' => true,
			'data'   => $villages,
		]);
	}

	/**
	 * Add a new region if it doesn't exist.
	 *
	 * Expects a POST payload:
	 * - name (string): The name of the region.
	 *
	 * @param Request $request
	 * @response array{status: bool, data: object}
	 * @throws ValidationException
	 */

	#[BodyParameter(
		'name', 
		description: 'Name of the region. Must be unique in the regions table.', 
		type: 'string', 
		example: 'South East', 
		required: true
	)]

	public function addRegion(Request $request): JsonResponse
	{

		$validated = $request->validate([
			'name' => 'required|string|max:255|unique:regions,name',
		]);

		$region = Region::firstOrCreate(
			['name' => $validated['name']]
		);

		return response()->json([
			'status' => true,
			'data'   => $region,
		]);
	}

	/**
	 * Add a new district if it doesn't exist.
	 *
	 * Expects a POST payload:
	 * - name (string): The name of the district.
	 * - region_id (int): The foreign key referencing the region.
	 *
	 * @param Request $request
	 * @response array{status: bool, data: object}
	 * @throws ValidationException
	 */

	#[BodyParameter(
		'name', 
		description: 'Name of the district. Must be unique in the districts table.', 
		type: 'string', 
		required: true,
		example:''
	)]

	#[BodyParameter(
		'region_id', 
		description: 'Region Id the district belongs to. Must exists in the regions table.', 
		type: 'int', 
		required: true,
	)]

	public function addDistrict(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'name'      => 'required|string|max:255|unique:districts,name',
			'region_id' => 'required|integer|exists:regions,id',
		]);

		$district = District::firstOrCreate(
			[
				'name'      => $validated['name'],
				'region_id' => $validated['region_id'],
			]
		);

		return response()->json([
			'status' => true,
			'data'   => $district,
		]);
	}

	/**
	 * Add a new county if it doesn't exist.
	 *
	 * Expects a POST payload:
	 * - name (string): The name of the county.
	 * - district_id (int): The foreign key referencing the district.
	 *
	 * @param Request $request
	 * @response array{status: bool, data: object}
	 * @throws ValidationException
	 */

	#[BodyParameter(
		'name', 
		description: 'Name of the County. Must be unique in the counties table.', 
		type: 'string', 
		required: true,
		example:''
	)]

	#[BodyParameter(
		'district_id', 
		description: 'District Id the county belongs to. Must exists in the districts table.', 
		type: 'int', 
		required: true,
	)]

	public function addCounty(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'name'        => 'required|string|max:255|unique:counties,name',
			'district_id' => 'required|integer|exists:districts,id',
		]);

		$county = County::firstOrCreate(
			[
				'name'        => $validated['name'],
				'district_id' => $validated['district_id'],
			]
		);

		return response()->json([
			'status' => true,
			'data'   => $county,
		]);
	}

	/**
	 * Add a new sub-county if it doesn't exist.
	 *
	 * Expects a POST payload:
	 * - name (string): The name of the sub-county.
	 * - county_id (int): The foreign key referencing the county.
	 *
	 * @param Request $request
	 * @response array{status: bool, data: object}
	 * @throws ValidationException
	 */

	#[BodyParameter(
		'name', 
		description: 'Name of the Sub County. Must be unique in the sub counties table.', 
		type: 'string', 
		required: true,
		example:''
	)]

	#[BodyParameter(
		'county_id', 
		description: 'County Id the Sub County belongs to. Must exists in the counties table.', 
		type: 'int', 
		required: true,
	)]

	public function addSubCounty(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'name'      => 'required|string|max:255|unique:sub_counties,name',
			'county_id' => 'required|integer|exists:counties,id',
		]);

		$subCounty = SubCounty::firstOrCreate(
			[
				'name'      => $validated['name'],
				'county_id' => $validated['county_id'],
			]
		);

		return response()->json([
			'status' => true,
			'data'   => $subCounty,
		]);
	}

	/**
	 * Add a new parish if it doesn't exist.
	 *
	 * Expects a POST payload:
	 * - name (string): The name of the parish.
	 * - sub_county_id (int): The foreign key referencing the sub-county.
	 *
	 * @param Request $request
	 * @response array{status: bool, data: object}
	 * @throws ValidationException
	 */

	#[BodyParameter(
		'name', 
		description: 'Name of the parish. Must be unique in the parishes table.', 
		type: 'string', 
		required: true,
		example:''
	)]

	#[BodyParameter(
		'sub_county_id', 
		description: 'Sub County Id the parish belongs to. Must exists in the sub_counties table.', 
		type: 'int', 
		required: true,
	)]

	public function addParish(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'name'          => 'required|string|max:255|unique:parishes,name',
			'sub_county_id' => 'required|integer|exists:sub_counties,id',
		]);

		$parish = Parish::firstOrCreate(
			[
				'name'          => $validated['name'],
				'sub_county_id' => $validated['sub_county_id'],
			]
		);

		return response()->json([
			'status' => true,
			'data'   => $parish,
		]);
	}

	/**
	 * Add a new village if it doesn't exist.
	 *
	 * Expects a POST payload:
	 * - name (string): The name of the village.
	 * - parish_id (int): The foreign key referencing the parish.
	 *
	 * @param Request $request
	 * @response array{status: bool, data: object}
	 * @throws ValidationException
	 */

	#[BodyParameter(
		'name', 
		description: 'Name of the village. Must be unique in the villages table.', 
		type: 'string', 
		required: true,
		example:''
	)]

	#[BodyParameter(
		'parish_id', 
		description: 'Parish Id the village belongs to. Must exists in the parishes table.', 
		type: 'int', 
		required: true,
	)]

	public function addVillage(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'name'      => 'required|string|max:255|unique:villages,name',
			'parish_id' => 'required|integer|exists:parishes,id',
		]);

		$village = Village::firstOrCreate(
			[
				'name'      => $validated['name'],
				'parish_id' => $validated['parish_id'],
			]
		);

		return response()->json([
			'status' => true,
			'data'   => $village,
		]);
	}

}
