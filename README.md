# PanaceaMobile for Laravel

<p>
    <a href="https://packagist.org/packages/emotality/panacea-laravel"><img src="https://img.shields.io/packagist/l/emotality/panacea-laravel" alt="License"></a>
    <a href="https://packagist.org/packages/emotality/panacea-laravel"><img src="https://img.shields.io/packagist/v/emotality/panacea-laravel" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/emotality/panacea-laravel"><img src="https://img.shields.io/packagist/dt/emotality/panacea-laravel" alt="Total Downloads"></a>
</p>

Laravel package for PanaceaMobile API.

<p>
    <a href="https://www.panaceamobile.com" target="_blank">
        <img src="https://emotality.com/development/GitHub/PanaceaMobile.png" height="50">
    </a>
</p>

## Requirements

- PHP 7.0+
- Laravel 5.5+

## Installation

1. `composer require emotality/panacea-laravel`
2. `php artisan vendor:publish --provider="Emotality\Panacea\PanaceaMobileServiceProvider"`
3. Add the following lines to your `.env` file:

```
PANACEA_USERNAME="<your_panacea_username>"
PANACEA_PASSWORD="<your_panacea_password>"
```

## Usage

### Send SMS to a single recipient:

```php
\PanaceaMobile::sms('+27820000001', "1st Line\n2nd Line\n3rd Line");
```

Response will be a `bool`, `true` if successful, `false` if unsuccessful.

---

### Send SMS to multiple recipients:

```php
\PanaceaMobile::smsMany(['+27820000001', '+27820000002'], "1st Line\n2nd Line\n3rd Line");
```

Response will be an array where the keys are the recipients' numbers, the values will be booleans:

```php
array:2 [▼
  "+27820000001" => true
  "+27820000002" => false
]
```

---

### Notifications:

```php
namespace App\Notifications;

use Emotality\Panacea\PanaceaChannel;
use Emotality\Panacea\PanaceaMessage;
use Illuminate\Notifications\Notification;

class ExampleNotification extends Notification
{
    public function via($notifiable)
    {
        return [PanaceaChannel::class];
    }
    
    // Send SMS to a single recipient
    public function toPanacea($notifiable) // Can also use toSms($notifiable)
    {
        return (new PanaceaMessage())
            ->to($notifiable->mobile) // Assuming $user->mobile
            ->message("1st Line\n2nd Line\n3rd Line");
    }
    
    // Send SMS to multiple recipients
    public function toPanacea($notifiable) // Can also use toSms($notifiable)
    {
        return (new PanaceaMessage())
            ->toMany(['+27820000001', '+27820000002'])
            ->message("1st Line\n2nd Line\n3rd Line");
    }
}
```

## License

panacea-laravel is released under the MIT license. See [LICENSE](https://github.com/emotality/panacea-laravel/blob/master/LICENSE) for details.
