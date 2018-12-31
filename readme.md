
## URL Status Code Checker

This package can be used to retrieve http status code of a particular url, as well as its headers.

- Lightweight, no dependencies.
- Can be configured easily with curl options.

## Requirements

This package only requires php curl and json extensions. PHP requirement is 5.5+.

## Installation

Add `muffeen/url-status` as a require dependency in your `composer.json` file:
```text
composer require muffeen/url-status 
```

## Usage

Refer to the example below:
```php
use Muffeen\UrlStatus\UrlStatus

$url_status = UrlStatus::get('http://www.example.com');
$http_status_code = $url_status->getStatusCode();
$response_headers = $url_status->getResponseHeaders();
```
Feel free to view the code on how you can extend the options how you can extend the way you run your request. Another example below is how you would want to change user agent of the request.
```php
use Muffeen\UrlStatus\Settings
use Muffeen\UrlStatus\UrlStatus

$settings = new Settings([
    'CURLOPT_USERAGENT' => '<user-agent-here>',
]);

$url_status = UrlStatus::get('http://www.example.com', $settings);
$http_status_code = $url_status->getStatusCode();
$response_headers = $url_status->getResponseHeaders();
```

## Questions

Feel free to open an issue for any question of special case or concern.

## Todo

- Add tests.
- Update this readme.

## Contributing

Thank you for considering contributing to this package. Though its not a big one but issues and pull requests are welcome.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).