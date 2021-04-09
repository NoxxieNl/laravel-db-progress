# Laravel-Db-Progress
[![Latest Stable Version](https://poser.pugx.org/noxxie/progress/v/stable)](https://packagist.org/packages/noxxie/progress)
[![Total Downloads](https://poser.pugx.org/noxxie/progress/downloads)](https://packagist.org/packages/noxxie/progress)
[![Latest Unstable Version](https://poser.pugx.org/noxxie/progress/v/unstable)](https://packagist.org/packages/noxxie/progress)
[![License](https://poser.pugx.org/noxxie/progress/license)](https://packagist.org/packages/noxxie/progress)

Laravel-Db-Progress is a simple odbc progress service provider for Laravel. It provides odbc connection by extending the Illuminate Database component of the laravel framework. It also provides the grammer changes in order to let everything work.

To connect to the Progress database we utilize the ODBC drivers provided from progress. These must be installed before you can
use this package. I found out [This website](https://blog.zedfox.us/installing-openedge-sql-client-access-odbc-drivers-ubuntu/) contains all the information you need to install those drivers.

---

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)

## Installation

Add the library using composer:
```
composer require "noxxie/laravel-db-progress"
```

### Configuration
You can put your Progress credentials into ``app/config/database.php`` file
using the following php lines:
```php
    'progress' => [
        'driver' => 'progress',
        'host' => env('PROGRESS_DB_HOST', 'localhost'),
        'port' => env('PROGRESS_DB_PORT', 19204),
        'database' => env('PROGRESS_DB_DATABASE', 'forge'),
        'username' => env('PROGRESS_DB_USERNAME', 'forge'),
        'password' => env('PROGRESS_DB_PASSWORD', ''),
        'codepage' => env('PROGRESS_DB_CODEPAGE', 'ISO_8859_1'),
        'schema' =>  env('PROGRESS_DB_SCHEMA', 'PUB'),
    ],
```

As you can see there is also room to define them in your `.env` file.

## Usage

Consult the [Laravel framework documentation](http://laravel.com/docs). Please be aware that some functionality will not work if you do not run the latest version of openedge. You will get an database exception when this accures. Consult the openedge documentation what was introduced in what version.