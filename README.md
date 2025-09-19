[![Packagist](https://img.shields.io/packagist/v/sevaske/discourse.svg?style=flat-square)](https://packagist.org/packages/sevaske/discourse)
[![PHPUnit](https://github.com/sevaske/discourse/actions/workflows/tests.yml/badge.svg)](https://github.com/sevaske/discourse/actions/workflows/tests.yml)
[![PHPStan](https://github.com/sevaske/discourse/actions/workflows/phpstan.yml/badge.svg)](https://github.com/sevaske/discourse/actions/workflows/phpstan.yml)

# Discourse Connect PHP Library

A lightweight PHP (^7.4) library for integrating with **Discourse Connect** (formerly Discourse SSO).  
It helps you parse the incoming `sso` payload, validate the signature, and generate a signed response payload for redirecting back to Discourse.

---

## Installation

Install via Composer:

```bash
composer require sevaske/discourse
```

## Usage

Discourse sends a signed payload to your endpoint with `sso` and `sig`. Build and sign the response payload and redirect back to discourse.

#### Notice
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
$request->nonce();
$request->returnUrl();

$response = (new ResponsePayload($signer))->build(
    $request->nonce(), 
    'my-user-id', 
    'myemail@mywebsite.com',
    [
        'name' => 'Naruto Uzumaki',
        'username' => 'konohaman',
        // ... other extra parameters
    ]
);

$redirectUrl = $request->returnUrl().'?'.$response;
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

