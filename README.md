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
PANACEA_FROM="<from_name>" // Optional
```

## Usage

### Sending an SMS to a single recipient:

```php
\PanaceaMobile::sms('+27820000001', "1st Line\n2nd Line\n3rd Line");
// or
\PanaceaMobile::sms('+27820000001', "1st Line\n2nd Line\n3rd Line", 'From Name');
```

Response will be a `bool`, `true` if successful, `false` if unsuccessful.

---

### Sending an SMS to multiple recipients:

```php
\PanaceaMobile::smsMany(['+27820000001', '+27820000002'], "1st Line\n2nd Line\n3rd Line");
// or
\PanaceaMobile::smsMany(['+27820000001', '+27820000002'], "1st Line\n2nd Line\n3rd Line", 'From Name');
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
        // Send SMS without "to", this value will automatically be fetched from
        // the "routeNotificationForPanacea" method in your notifiable entity.
        // See the "Routing SMS Notifications" section below.
        return (new PanaceaMobileSms())->message("1st Line\n2nd Line\n3rd Line");
        
        // or send SMS to a single recipient, specifying the "to" value
        return (new PanaceaMobileSms())
            ->to($notifiable->mobile) // Assuming $user->mobile is their mobile number
            ->from('From Name') // Optional. Will override config's "from" value.
            ->message("1st Line\n2nd Line\n3rd Line");
            
        // or send SMS to multiple recipients
        return (new PanaceaMobileSms())
            ->toMany(['+27820000001', '+27820000002'])
            ->from('From Name') // Optional. Will override config's "from" value.
            ->message("1st Line\n2nd Line\n3rd Line");
    }
}
```

### Routing SMS Notifications

To route Panacea notifications to the proper phone number, define a `routeNotificationForPanacea` method on your notifiable entity:

```php
namespace App\Models;
 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 
class User extends Authenticatable
{
    use Notifiable;
 
    /**
     * Route notifications for the Panacea channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForPanacea($notification)
    {
        return $this->mobile;
    }
}
```

## License

panacea-laravel is released under the MIT license. See [LICENSE](https://github.com/emotality/panacea-laravel/blob/master/LICENSE) for details.
