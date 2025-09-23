[![Packagist](https://img.shields.io/packagist/v/sevaske/discourse.svg?style=flat-square)](https://packagist.org/packages/sevaske/discourse)
[![PHPUnit](https://github.com/sevaske/discourse/actions/workflows/tests.yml/badge.svg)](https://github.com/sevaske/discourse/actions/workflows/tests.yml)
[![PHPStan](https://github.com/sevaske/discourse/actions/workflows/phpstan.yml/badge.svg)](https://github.com/sevaske/discourse/actions/workflows/phpstan.yml)

# Discourse PHP SDK (Unofficial)

This is an **unofficial SDK** for interacting with the [Discourse API](https://docs.discourse.org/) & [Discourse Connect (SSO)](https://meta.discourse.org/t/setup-discourseconnect-official-single-sign-on-for-discourse-sso/13045) in PHP.  
It provides a clean, PSR-compliant abstraction on top of the Discourse API, so you can interact with your forum programmatically in a structured way.


## Requirements

- PHP ^7.4 || ^8.0
- PSR-18 HTTP Client (`psr/http-client`)
- PSR-17 HTTP Factories (`psr/http-factory`, `psr/http-message`)
- A PSR-compliant HTTP implementation such as:
    - [Guzzle](https://github.com/guzzle/guzzle)
    - [Symfony HttpClient](https://symfony.com/doc/current/http_client.html)
    - [HTTPlug](https://github.com/php-http/httplug)


## Notes

- This library is **not an official Discourse SDK**.
- It’s intended to provide a strongly typed, PSR-compliant abstraction that can be used in Laravel, Symfony, or plain PHP projects.


## Installation

Install via Composer:

```bash
composer require sevaske/discourse
```


## Usage

### API

```php
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Sevaske\Discourse\Services\Api;

$discourseUrl = 'https://meta.discourse.com'; // your forum url
$discourseApiKey = 'your-api-key';
$discourseApiUsername = 'your-username';

$httpFactory = new HttpFactory();
$client = new Client([
    'base_uri' => $discourseUrl,
    'headers' => [
        'Api-Key' => $discourseApiKey,
        'Api-Username' => $discourseApiUsername,
    ],
]);

$api = new Api($client, $httpFactory, $httpFactory);

// for examole, invites
$response = $api->invites()->create('some@email.com');
$response->getHttpStatusCode(); // 200
$response->link || $response['link']; // meta.discourse.com/invites/qwerty

// make custom request
$response = $api->request('GET', '/categories.json', [
    'include_subcategories' => true,
]);
```

#### API References

###### Badges

```php
$api->badges()->list();
$api->badges()->create(string $name, int $badgeTypeId);
$api->badges()->update(int $id, string $name, int $badgeTypeId);
$api->badges()->delete(int $id);

```

###### Categories

```php
$api->categories()->list(?bool $includeSubcategories = null);
$api->categories()->get(int $id);
$api->categories()->create(string $name, array $extra = []);
$api->categories()->update(int $id, string $name, array $extra = []);
```

###### Groups

```php
$api->groups()->list();
$api->groups()->get(int|string $nameOrId, bool $byId = true);
$api->groups()->create(string $name, array $extra = []);
$api->groups()->update(int $id, string $name, array $extra = []);
$api->groups()->delete(int $id);

$api->groups()->getMembers(int $groupId);
$api->groups()->addMembers(int $groupId, array $usernames);
$api->groups()->removeMembers(int $groupId, array $usernames);
```

###### Invites

```php
$api->invites()->create(
    string $email,
    bool $skipEmail = false,
    ?string $customMessage = null,
    ?int $maxRedemptionsAllowed = 1,
    ?int $topicId = null,
    ?string $groupIds = null,
    ?string $groupNames = null,
    ?string $expiresAt = null
);
```

##### Notifications

```php
$api->notifications()->list();
$api->notifications()->read(?int $id); // null to read all
```

##### Posts

```php
$api->posts()->latest(?int $before = null);
$api->posts()->get(int $id);
$api->posts()->create(array $data);
$api->posts()->update(int $id, string $raw, ?string $editReason = null);
$api->posts()->delete(int $id);

$api->posts()->lock(int $id);
$api->posts()->unlock(int $id);

$api->posts()->replies(int $id);
$api->posts()->action(int $postId, int $postActionTypeId, ?bool $flagTopic = null);
```

##### Users

```php
$api->users()->list(
    ?string $flag = null,   // "active", "new", "staff", "suspended", "blocked", "suspect"
    ?string $order = null,
    ?bool $asc = null,
    ?int $page = null,
    ?bool $showEmails = null,
    ?bool $stats = null,
    ?string $email = null,
    ?string $ip = null
);

$api->users()->getById(int $id);
$api->users()->getByUsername(string $username);
$api->users()->getByExternalId(string $externalId);

$api->users()->create(string $name, string $email, string $password, string $username, array $extra = []);
$api->users()->update(string $username, string $name, array $extra);

$api->users()->changePassword(string $token, string $username, string $password);
$api->users()->sendPasswordResetEmail(string $login);

$api->users()->delete(int $id, ?bool $deletePosts = null, ?bool $blockEmail = null, ?bool $blockUrls = null, ?bool $blockIp = null);

$api->users()->activate(int $id);
$api->users()->deactivate(int $id);
$api->users()->logoutUser(int $id);

$api->users()->badges(string $username);
```

##### Site

```php
$api->site()->info();
$api->site()->basicInfo();
```

#### Extending with Macros

This SDK uses a **`Macroable` trait** (inspired by Laravel), which allows you to add new methods to the `Api` class at runtime.

### Example: Simple Macro

```php
use Sevaske\Discourse\Services\Api;

Api::macro('ping', function () {
    return 'pong';
});

$api = new Api($client, $httpFactory, $httpFactory);
$api->ping(); // "pong"
```

### Connect (SSO)

Discourse sends a signed payload to your endpoint with `sso` and `sig`. Build and sign the response payload and redirect back to discourse.

##### Notice
You should **always validate the signature** before using the payload.

```php
use Sevaske\Discourse\Exceptions\InvalidRequestSignature;
use Sevaske\Discourse\Services\Signer;
use Sevaske\Discourse\Services\Connect\RequestPayload;
use Sevaske\Discourse\Services\Connect\ResponsePayload;

$signer = new Signer('your-discourse-secret');

$sso = $_GET['sso'] ?? '';
$sig = $_GET['sig'] ?? '';

if (! $signer->validate($sig, $sso)) {
    throw new InvalidRequestSignature;
}

$request = new RequestPayload($sso);
$response = (new ResponsePayload($signer))->build(
    $request->nonce(), 
    'my-user-id', 
    'myemail@mywebsite.com',
    [ // optional params
        'name' => 'Naruto Uzumaki',
    ]
);

$redirectUrl = $request->buildReturnUrl($response);
```

## Running Tests

This library uses **PHPUnit** for testing.  
You can run tests via Composer:

```bash
composer test
```

Also, you can run **stan**
```bash
composer stan
```

## License

MIT License

