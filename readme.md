# Progress


Progress is a simple odbc progress service provider for Laravel. It provides odbc Connection by extending the Illuminate Database component of the laravel framework.

---

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)

## Installation
Add Progress to your composer.json file:
```
"require": {
    "noxxie/progress": "~1.0"
}
```
Use [composer](http://getcomposer.org) to install this package.
```
$ composer update
```

### Configuration
You can put your Progress credentials into ``app/config/database.php`` file.

#### Option 1: Configure Progress using ``app/config/database.php`` file
Simply add this code at the end of your ``app/config/database.php`` file:

```php
    /*
    |--------------------------------------------------------------------------
    | Progress Database
    |--------------------------------------------------------------------------
    */

    'progress' => [
        'driver' => 'progress',
        'username' => '',
        'password' => '',
        'owner' => '',
        'driverName' => '',
        'options' => [
            PDO::ATTR_CASE => PDO::CASE_LOWER
        ]
    ],

```

## Usage

Consult the [Laravel framework documentation](http://laravel.com/docs).