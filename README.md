# PanaceaMobile API for Laravel

[![Packagist License](https://poser.pugx.org/emotality/panacea-laravel/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/emotality/panacea-laravel/version.png)](https://packagist.org/packages/emotality/panacea-laravel)
[![Total Downloads](https://poser.pugx.org/emotality/panacea-laravel/d/total.png)](https://packagist.org/packages/emotality/panacea-laravel)

Laravel package for [PanaceaMobile](https://www.panaceamobile.com) API.

## Installation

1. `composer require emotality/panacea-laravel`
2. `php artisan vendor:publish --provider="Emotality\Panacea\PanaceaMobileServiceProvider"`
3. Add the following lines to your `.env` file:

```
PANACEA_USERNAME="<your_panacea_username>"
PANACEA_PASSWORD="<your_panacea_password>"
```

---

Laravel 5.5+ will use the auto-discovery function but for Laravel 5.4 and lower, you will need to include the service provider & facade manually in `config/app.php`:

```php
'providers' => [
    ...,
    /*
     * Package Service Providers...
     */
    Emotality\Panacea\PanaceaMobileServiceProvider::class,
    ...,
];

...

'aliases' => [
    ...,
    'PanaceaMobile' => Emotality\Panacea\PanaceaMobile::class,
];
```

## Usage

Import `PanaceaMobile` class:

```php
use Emotality\Panacea\PanaceaMobile;
```

#### Send SMS to a single recipient:

```php
PanaceaMobile::sms('+27820000001', "1st Line\n2nd Line\n3rd Line");
```

Response will be a `bool`, `true` if successful, `false` if unsuccessful.

#### Send SMS to multiple recipients:

```php
PanaceaMobile::smsMany(['+27820000001', '+27820000002'], "1st Line\n2nd Line\n3rd Line")
```

Response will be an array where the keys are the recipients' numbers, the values will be booleans:

```php
array:2 [▼
  "+27820000001" => true
  "+27820000002" => false
]
```

## License

panacea-laravel is released under the MIT license. See [LICENSE](https://github.com/emotality/panacea-laravel/blob/master/LICENSE) for details.
