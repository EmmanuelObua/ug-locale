<?php

namespace Intanode\UgLocale\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Intanode\UgLocale\Models\{District, County, SubCounty, Parish, Village, Region};

/**
 * Class UgLocaleController
 * Handles CRUD operations for Ugandan administrative divisions.
 */
class UgLocaleController extends Controller
{
	
	/**
     * Retrieve all regions.
     *
     * @return JsonResponse
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
     * @return JsonResponse
     */
    public function getDistrictsByRegion(Request $request): JsonResponse
    {
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
     * @return JsonResponse
     */
    public function getCountiesByDistrict(Request $request): JsonResponse
    {
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
     * @return JsonResponse
     */
    public function getSubCountiesByCounty(Request $request): JsonResponse
    {
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
     * @return JsonResponse
     */
    public function getParishesBySubCounty(Request $request): JsonResponse
    {
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
     * @return JsonResponse
     */
    public function getVillagesByParish(Request $request): JsonResponse
    {
        $parishId = (int) $request->input('parish_id');

        $villages = Village::where('parish_id', $parishId)->get();

        return response()->json([
            'status' => true,
            'data'   => $villages,
        ]);
    }


    // ============================================================
    // "Add" Endpoints for Creating New Location Records
    // ============================================================

    /**
     * Add a new region if it doesn't exist.
     *
     * Expects a POST payload:
     * - name (string): The name of the region.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addRegion(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addDistrict(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addCounty(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addSubCounty(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addParish(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addVillage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
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
