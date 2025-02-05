<?php

use Illuminate\Support\Facades\Route;
use Intanode\UgLocale\Controllers\UgLocaleController;

Route::prefix('ug-locale')->group(function () {

    /**
	* Get all regions.
	*
	* URL: GET /ug-locale/regions
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	Route::get('/regions', [UgLocaleController::class, 'getRegions'])
			->name('locations.regions');

    /**
	* Get districts by region.
	*
	* URL: GET /ug-locale/districts?region_id={region_id}
	* Query Parameters:
	* - region_id (int): The ID of the region.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	Route::get('/districts', [UgLocaleController::class, 'getDistrictsByRegion'])
			->name('locations.districts');

    /**
	* Get counties by district.
	*
	* URL: GET /ug-locale/counties?district_id={district_id}
	* Query Parameters:
	* - district_id (int): The ID of the district.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	Route::get('/counties', [UgLocaleController::class, 'getCountiesByDistrict'])
			->name('locations.counties');

    /**
	* Get sub-counties by county.
	*
	* URL: GET /ug-locale/sub-counties?county_id={county_id}
	* Query Parameters:
	* - county_id (int): The ID of the county.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	Route::get('/sub-counties', [UgLocaleController::class, 'getSubCountiesByCounty'])
			->name('locations.sub_counties');

    /**
	* Get parishes by sub-county.
	*
	* URL: GET /ug-locale/parishes?sub_county_id={sub_county_id}
	* Query Parameters:
	* - sub_county_id (int): The ID of the sub-county.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	Route::get('/parishes', [UgLocaleController::class, 'getParishesBySubCounty'])
			->name('locations.parishes');

    /**
	* Get villages by parish.
	*
	* URL: GET /ug-locale/villages?parish_id={parish_id}
	* Query Parameters:
	* - parish_id (int): The ID of the parish.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	Route::get('/villages', [UgLocaleController::class, 'getVillagesByParish'])
			->name('locations.villages');


    /*
    * POST Endpoints for Creating Location Records If Missing
    */

   /**
    * Add a new region if it doesn't exist.
    *
    * URL: POST /ug-locale/regions
    * Payload:
    * - name (string): The name of the region.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   Route::post('/regions', [LocationController::class, 'addRegion'])
   		->name('locations.add_region');

   /**
    * Add a new district if it doesn't exist.
    *
    * URL: POST /ug-locale/districts
    * Payload:
    * - name (string): The name of the district.
    * - region_id (int): The foreign key referencing the region.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   Route::post('/districts', [LocationController::class, 'addDistrict'])
   		->name('locations.add_district');

   /**
    * Add a new county if it doesn't exist.
    *
    * URL: POST /ug-locale/counties
    * Payload:
    * - name (string): The name of the county.
    * - district_id (int): The foreign key referencing the district.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   Route::post('/counties', [LocationController::class, 'addCounty'])
   		->name('locations.add_county');

   /**
    * Add a new sub-county if it doesn't exist.
    *
    * URL: POST /ug-locale/sub-counties
    * Payload:
    * - name (string): The name of the sub-county.
    * - county_id (int): The foreign key referencing the county.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   Route::post('/sub-counties', [LocationController::class, 'addSubCounty'])
   		->name('locations.add_sub_county');

   /**
    * Add a new parish if it doesn't exist.
    *
    * URL: POST /ug-locale/parishes
    * Payload:
    * - name (string): The name of the parish.
    * - sub_county_id (int): The foreign key referencing the sub-county.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   Route::post('/parishes', [LocationController::class, 'addParish'])
   		->name('locations.add_parish');

   /**
    * Add a new village if it doesn't exist.
    *
    * URL: POST /ug-locale/villages
    * Payload:
    * - name (string): The name of the village.
    * - parish_id (int): The foreign key referencing the parish.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   Route::post('/villages', [LocationController::class, 'addVillage'])
   		->name('locations.add_village');
});
