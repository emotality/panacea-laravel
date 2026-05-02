<?php

use Emotality\Panacea\PanaceaException;
use Emotality\Panacea\PanaceaMobileAPI;
use Emotality\Panacea\PanaceaMobileFacade;
use Emotality\Panacea\PanaceaMobileSms;
use Emotality\Panacea\PanaceaMobileSmsChannel;
use Illuminate\Http\Client\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

function panaceaQuery(Request $request): array
{
    parse_str(parse_url($request->url(), PHP_URL_QUERY) ?: '', $query);

    return $query;
}

function configurePanacea(array $overrides = []): void
{
    Config::set('panacea', array_merge([
        'username' => 'panacea-user',
        'password' => 'panacea-password',
        'from' => null,
        'exceptions' => false,
    ], $overrides));
}

it('sends an sms through the PanaceaMobile API', function () {
    configurePanacea(['from' => 'Acme']);
    Http::fake([
        'eu-api.panaceamobile.com/*' => Http::response(['status' => 1]),
    ]);

    expect(PanaceaMobileFacade::sms('27820000001', 'Hello'))->toBeTrue();

    Http::assertSent(function (Request $request) {
        expect($request->url())->toStartWith('https://eu-api.panaceamobile.com/json?');

        return panaceaQuery($request) === [
            'action' => 'message_send',
            'username' => 'panacea-user',
            'password' => 'panacea-password',
            'from' => 'Acme',
            'to' => '+27820000001',
            'text' => 'Hello',
        ];
    });
});

it('fails before making an API request when credentials are missing', function () {
    configurePanacea(['username' => null, 'password' => null]);
    Http::fake();

    PanaceaMobileFacade::sms('+27820000001', 'Hello');
})->throws(PanaceaException::class, 'Your PanaceaMobile username and password is required!');

it('rejects sender names that are not alpha numeric', function () {
    configurePanacea();
    Http::fake();
    Log::spy();

    expect(PanaceaMobileFacade::sms('+27820000001', 'Hello', 'Bad Sender'))->toBeFalse();

    Http::assertNothingSent();
    Log::shouldHaveReceived('critical')
        ->once()
        ->with('PanaceaMobile SMS Error: "The "from" field can only contain alpha numeric characters! [+27820000001]"');
});

it('returns false and logs the API error when exceptions are disabled', function () {
    configurePanacea();
    Http::fake([
        'eu-api.panaceamobile.com/*' => Http::response(['status' => 0, 'details' => 'Invalid recipient'], 200),
    ]);
    Log::spy();

    expect(PanaceaMobileFacade::sms('+27820000001', 'Hello'))->toBeFalse();

    Log::shouldHaveReceived('critical')
        ->once()
        ->with('PanaceaMobile SMS Error: "Invalid recipient [+27820000001]"');
});

it('throws the API error when exceptions are enabled', function () {
    configurePanacea(['exceptions' => true]);
    Http::fake([
        'eu-api.panaceamobile.com/*' => Http::response(['status' => 0, 'message' => 'Rejected'], 200),
    ]);

    PanaceaMobileFacade::sms('+27820000001', 'Hello');
})->throws(PanaceaException::class, 'Rejected [+27820000001]');

it('sends many sms messages once per unique recipient', function () {
    $api = new class extends PanaceaMobileAPI
    {
        /** @var array<int, array{recipient: string, message: string, from: string|null}> */
        public array $sent = [];

        public function __construct() {}

        public function sendSms(string $recipient, string $message, ?string $from = null): bool
        {
            $this->sent[] = compact('recipient', 'message', 'from');

            return $recipient !== '+27820000002';
        }
    };

    app()->instance(PanaceaMobileAPI::class, $api);

    expect(PanaceaMobileFacade::smsMany([
        '+27820000001',
        '+27820000002',
        '+27820000001',
    ], 'Hello', 'Acme'))->toBe([
        '+27820000001' => true,
        '+27820000002' => false,
    ]);

    expect($api->sent)->toBe([
        ['recipient' => '+27820000001', 'message' => 'Hello', 'from' => 'Acme'],
        ['recipient' => '+27820000002', 'message' => 'Hello', 'from' => 'Acme'],
    ]);
});

it('routes notification sms messages through the Panacea channel', function () {
    Config::set('panacea.from', 'Acme');

    $api = new class extends PanaceaMobileAPI
    {
        /** @var array<int, array{recipient: string, message: string, from: string|null}> */
        public array $sent = [];

        public function __construct() {}

        public function sendSms(string $recipient, string $message, ?string $from = null): bool
        {
            $this->sent[] = compact('recipient', 'message', 'from');

            return true;
        }
    };

    $notifiable = new class
    {
        public function routeNotificationFor(string $driver, Notification $notification): string
        {
            return '+27820000001';
        }
    };

    $notification = new class extends Notification
    {
        public function toPanacea(object $notifiable): PanaceaMobileSms
        {
            return (new PanaceaMobileSms)->message('Hello');
        }
    };

    app()->instance(PanaceaMobileAPI::class, $api);

    (new PanaceaMobileSmsChannel)->send($notifiable, $notification);

    expect($api->sent)->toBe([
        ['recipient' => '+27820000001', 'message' => 'Hello', 'from' => 'Acme'],
    ]);
});
