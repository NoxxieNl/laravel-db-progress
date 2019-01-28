# Progress
[![Latest Stable Version](https://poser.pugx.org/noxxie/progress/v/stable)](https://packagist.org/packages/noxxie/progress)
[![Total Downloads](https://poser.pugx.org/noxxie/progress/downloads)](https://packagist.org/packages/noxxie/progress)
[![Latest Unstable Version](https://poser.pugx.org/noxxie/progress/v/unstable)](https://packagist.org/packages/noxxie/progress)
[![License](https://poser.pugx.org/noxxie/progress/license)](https://packagist.org/packages/noxxie/progress)

Progress is a simple odbc progress service provider for Laravel. It provides odbc connection by extending the Illuminate Database component of the laravel framework. This package is primarily ment for **windows** OS. Although when configured correctly you can also use the odbc connection manager in Linux to use this package.

---

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)

## Installation
Add Progress to your composer.json file:
```
"require": {
    "noxxie/progress-laravel": "^1.0"
}
```
Use [composer](http://getcomposer.org) to install this package.
```
$ composer update
```

### Configuration
You can put your Progress credentials into ``app/config/database.php`` file.

#### Configure Progress using ``app/config/database.php`` file
Simply add this code at the end of your ``app/config/database.php`` file:

```php
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

- driver name must be `progress`
- Username is your SQL username
- Password is your SQL password
- Owner specifies the owner scheme within progress (Example: `PUB`)
- driverName can be left empty
- Options, default option within Laravel here you can specify extra PDO options to be set when the database connection is made

## Usage

Consult the [Laravel framework documentation](http://laravel.com/docs). Please be aware that some functionality will not work if you do not run the latest version of openedge. You will get an database exception when this accures. Consult the openedge documentation what was introduced in what version.