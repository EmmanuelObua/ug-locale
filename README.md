# UG Locale

**UG Locale** is a Laravel package that provides a comprehensive set of resources to manage Ugandan location data (regions, districts, counties, sub-counties, parishes, and villages). This package includes configuration options, database migrations, and routes for a fully featured locale management system.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Publishing Package Assets](#publishing-package-assets)
	- [Configuration](#configuration-file)
	- [Migrations](#migrations)
	- [Routes](#routes)
- [Usage](#usage)
	- [Adding the Routes to Your Application](#adding-the-routes-to-your-application)
	- [Installing Location Data](#installing-location-data)
- [Routes Overview](#routes-overview)
- [Additional Documentation](#additional-documentation)
- [License](#license)

## Installation

Install the package via Composer:

```bash
composer require intanode/ug-locale
```

## Configuration

After installing the package, you need to publish the configuration file. This file allows you to adjust various settings, such as the batch size for seeding records. The default value works perfectly for most applications, but you can modify it to suit your needs.

## Publishing Package Assets

## Configuration File

Run the following Artisan command to publish the configuration file:

```bash
php artisan vendor:publish --tag=ug-locale-config
```

Once published, you can find the configuration file (typically config/ugLocale.php). Open it to adjust the batch size for seeding the location records if necessary.

## Publishing Other Assets

The package provides several assets that you need to publish into your Laravel application. Use the following commands:

## Migrations

Publish the migration files that create the necessary tables for the location data:

```bash
php artisan vendor:publish --tag=ug-locale-migrations
```

## Routes

Publish the routes file that gives you access to the locale resources:

```bash
php artisan vendor:publish --tag=ug-locale-routes
```

After publishing, the routes file (e.g., ugLocaleRoutes.php) will be available in your project.
Usage

## Adding the Routes to Your Application

To make the published routes available, you must include the routes file in your application's routes. Open your web.php file (usually located in the routes folder) and add the following line at the bottom:

```php
require __DIR__.'/ugLocaleRoutes.php';
```

## Installing Location Data

After you have published the migrations and configuration, run the following command to migrate and seed the location tables:

```bash
php artisan locale:install
```

This command will execute the migrations and populate the tables with the location data.

## Routes Overview

Once installed, the package provides the following routes to interact with the locale resources:

GET Routes

```
	GET /ug-locale/regions
	Returns all regions.

	GET /ug-locale/districts?region_id={region_id}
	Returns all districts within a specified region.

	GET /ug-locale/counties?district_id={district_id}
	Returns all counties within a specified district.

	GET /ug-locale/sub-counties?county_id={county_id}
	Returns all sub-counties within a specified county.

	GET /ug-locale/parishes?sub_county_id={sub_county_id}
	Returns all parishes within a specified sub-county.

	GET /ug-locale/villages?parish_id={parish_id}
	Returns all villages within a specified parish.
```


POST Routes

```
	POST /ug-locale/regions
	Creates a new region if it does not exist.
	Payload: { "name": "Region Name" }

	POST /ug-locale/districts
	Creates a new district if it does not exist.
	Payload: { "name": "District Name", "region_id": 1 }

	POST /ug-locale/counties
	Creates a new county if it does not exist.
	Payload: { "name": "County Name", "district_id": 1 }

	POST /ug-locale/sub-counties
	Creates a new sub-county if it does not exist.
	Payload: { "name": "Sub-County Name", "county_id": 1 }

	POST /ug-locale/parishes
	Creates a new parish if it does not exist.
	Payload: { "name": "Parish Name", "sub_county_id": 1 }

	POST /ug-locale/villages
	Creates a new village if it does not exist.
	Payload: { "name": "Village Name", "parish_id": 1 }
```

## Additional Documentation

For more detailed information about each component of the package, please refer to the following sections:

# Configuration Options:
Check out the config/ugLocale.php file for available settings, including the batch size for seeding.

# Database Migrations:
Review the migration files published under your migrations directory for the table schema details.

# Controllers and Routes:
The LocationController contains the logic for handling requests. The routes file (ugLocaleRoutes.php) documents the available endpoints.

# Commands:
The Artisan command locale:install will automatically run your migrations and seed the database with initial location data.

# License

This package is open-sourced software licensed under the MIT license.

# With these instructions, your Laravel application is now ready to manage Ugandan location data using the UG Locale package. Enjoy seamless integration and extended locale functionality!