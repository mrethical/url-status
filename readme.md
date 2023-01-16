
# URL Status Code Checker

This package can be used to retrieve http status code of a particular url, as well as its headers.

- Easy to use.
- Lightweight, no dependencies.
- Can be configured easily with curl options.

## Requirements

This package only requires php curl and json extensions. PHP requirement is 5.3+.

## Installation

Add `muffeen/url-status` as a require dependency in your `composer.json` file:
```text
composer require muffeen/url-status 
```

## Usage

Refer to the example below:
```php
use Muffeen\UrlStatus\UrlStatus;

$urlStatus = UrlStatus::get('http://www.example.com');
$httStatusCode = $urlStatus->getStatusCode();
$responseHeaders = $urlStatus->getResponseHeaders();
```
Extend your request by using curl options constants. The example below show how can you set a user agent for your request.
```php
use Muffeen\UrlStatus\UrlStatus;

$urlStatus = UrlStatus::get('http://www.example.com', array(
    CURLOPT_USERAGENT => '<user-agent-here>',
));
$httStatusCode = $urlStatus->getStatusCode();
$responseHeaders = $urlStatus->getResponseHeaders();
```

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
