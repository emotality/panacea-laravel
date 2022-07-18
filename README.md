# PanaceaMobile for Laravel

<p>
    <a href="https://packagist.org/packages/emotality/panacea-laravel"><img src="https://img.shields.io/packagist/l/emotality/panacea-laravel" alt="License"></a>
    <a href="https://packagist.org/packages/emotality/panacea-laravel"><img src="https://img.shields.io/packagist/v/emotality/panacea-laravel" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/emotality/panacea-laravel"><img src="https://img.shields.io/packagist/dt/emotality/panacea-laravel" alt="Total Downloads"></a>
</p>

Laravel package to send transactional SMSes via PanaceaMobile.

<p>
    <a href="https://www.panaceamobile.com" target="_blank">
        <img src="https://emotality.com/development/GitHub/PanaceaMobile.png" height="50">
    </a>
</p>

## Requirements

- PHP 7.2+
- Laravel 7.0+

## Installation

1. `composer require emotality/panacea-laravel`
2. `php artisan vendor:publish --provider="Emotality\Panacea\PanaceaMobileServiceProvider"`
3. Add the following lines to your `.env` file:

```
PANACEA_USERNAME="<panacea_username>"
PANACEA_PASSWORD="<panacea_password_or_api_key>"
PANACEA_FROM="<from_name>" // Optional, but required for sending outside ZA
```

## Usage

### Sending an SMS to a single recipient:

```php
\PanaceaMobile::sms('+27820000001', "1st Line\n2nd Line\n3rd Line");
```

Response will be a `bool`, `true` if successful, `false` if unsuccessful.

---

### Sending an SMS to multiple recipients:

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

### Sending an SMS via notification:

```php
namespace App\Notifications;

use Emotality\Panacea\PanaceaMobileSms;
use Emotality\Panacea\PanaceaMobileSmsChannel;
use Illuminate\Notifications\Notification;

class ExampleNotification extends Notification
{
    // Notification channels
    public function via($notifiable)
    {
        return [PanaceaMobileSmsChannel::class];
    }
    
    // Send SMS
    public function toSms($notifiable) // Can also use toPanacea($notifiable)
    {
        // Send SMS to a single recipient
        return (new PanaceaMobileSms())
            ->to($notifiable->mobile) // Assuming $user->mobile
            ->message("1st Line\n2nd Line\n3rd Line");
            
        // or send SMS to multiple recipients
        return (new PanaceaMobileSms())
            ->toMany(['+27820000001', '+27820000002'])
            ->message("1st Line\n2nd Line\n3rd Line");
    }
}
```

## License

panacea-laravel is released under the MIT license. See [LICENSE](https://github.com/emotality/panacea-laravel/blob/master/LICENSE) for details.
