
# Passmarked PHP



Use the Passmarked Web API with simple and convenient object oriented PHP. You will require an API token and credit
which can be obtained by registering on [Passmarked](https://passmarked.com) and generating a token. Passmarked PHP
does not include bindings to Passmarked's CLI toolkit. The Passmarked CLI tool lives in [this repo](https://github.com/passmarked/passmarked). 

## About
Passmarked PHP is based on [Guzzle 6](https://github.com/guzzle/guzzle) and [Guzzle Psr7](https://github.com/guzzle/psr7)
and conforms to the [PSR-7](http://www.php-fig.org/psr/psr-7/) standard. Passmarked PHP can also be used with any other
PSR-7 compatible HTTP client by using the **Passmarked\RequestFactory** class to generate **GuzzleHttp\Psr7\Request** instances
that implement **Psr\Http\Message\RequestInterface**.

See the usage examples below for more details.

## Install

### Composer
N.B Not yet available
```bash
composer require passmarked/passmarked
```
N.B Not yet available
View the project at [packagist.org/packages/passmarked/passmarked](https://packagist.org/packages/passmarked/passmarked).

### From Source

To build from source:

```bash
git clone git@github.com:passmarked/php.git passmarked-php/
cd passmarked-php/
composer install
```

## How to use
You can use Passmarked PHP in a variety of ways: 
* The easiest way is to use the **Passmarked\Client** class to do the HTTP request 
and return a **Passmarked\Helper** object which will give you easy access to the
information returned by the Web API. The simplest example of using Passmarked PHP in
this way, looks like this:
```php
$config = [
    'api_token'     => 'PASSMARKED_API_TOKEN', // required
    'api_url'      => 'https://api.passmarked.com', // optional / default
    'api_version'   => '2',     // optional / default
    'http_version'  => '1.1',   // optional / default
    'telemetry'     => true     // optional / default
];
$passmarked = new \Passmarked\Client($config); // Get one on passmarked.com
$result = $passmarked->create();
echo $result->status
// Outputs "ok" or "error" if the Web API returned an error. 
```
Any other config options will be passed to the underlying **GuzzleHttp\Client** constructor.
For supported options see [Guzzle's Quickstart](http://docs.guzzlephp.org/en/latest/quickstart.html). However `base_uri`
will always be replaced with `api_url` or fallback to the default `https://api.passmarked.com`.

* Alternatively, you can generate **GuzzleHttp\Psr7\Request** objects using the **Passmarked\RequestFactory**
and execute the request with another PSR-7 compatible client:
```php
$request_factory = \Passmarked\RequestFactory($config);
$psr7_request = $request_factory->getWebsites();
```
* You can also "***unwrap***" the helpers to get the **GuzzleHttp\Psr7\Response** from them.
Just use **getPsr7Response()** on a helper:
```php
$websites_helper = $client->getWebsites();
$psr7_response = $websites_helper->getPsr7Response();
```

### Create
Create is the only method of **Passmarked\Client** that expects an array of arguments.
Supported options:
```php
$options = [
    'url'       => 'http://somesite.com', // The site to test
    'token'     => 'myapitoken', // Optional, falls back to your client config
    'recursive' => false,   // Optional
    'limit'     => 0,       // Optional
    'bail'      => false,   // Optional
    'level'     => 0,       // Optional
    'patterns' => [],       // Optional
]
$create_helper = $passmarked->create($options);
echo $create_helper->uid;
echo $create_helper->status;
```

### getWebsites
```php
$websites_helper = $client->getWebsites();
```

### getWebsite
```php
$website_helper = $client->getWebsite($key);
```

### getReport
```php
$report_helper = $client->getReport($uid);
```

### getProfile
```php
$profile_helper = $client->getProfile();
```

### getBalance
```php
$balance_helper = $client->getBalance();
```
All methods except create optionally accept a API token string as the last parameter:
```php
$websites_helper = $client->getWebsites$('PASSMARKED_API_TOKEN');
$website_helper = $client->getWebsite($uid,'PASSMARKED_API_TOKEN');
$report_helper = $client->getReport($uid,'PASSMARKED_API_TOKEN');
$profile_helper = $client->getProfile('PASSMARKED_API_TOKEN');
$balance_helper = $client->getBalance('PASSMARKED_API_TOKEN');
```

## Full Examples
```php
$config = [
    'api_token'     => 'PASSMARKED_API_TOKEN', // Get one on passmarked.com
];
$client = new \Passmarked\Client($config);

$create_helper = $client->create(['url' => 'http://www.github.com']);

echo "Response Status: {$report->status}";
echo "UID: {$create_helper->uid}";
// or
echo "Response Status: ".$report->get('status');
echo "UID: ".$create_helper->get('uid');
```

### Getting the PSR-7 Response
```php
$config = [
    'api_token'     => 'PASSMARKED_API_TOKEN', // Get one on passmarked.com
];
$client = new \Passmarked\Client($config);

$response = $client->create(['url' => 'http://www.github.com'])->getPsr7Response();

echo $response->getBody()->getContents();
```

### Generating PSR-7 Requests
```php
$config = [
    'api_token'     => 'PASSMARKED_API_TOKEN', // Get one on passmarked.com
];
$request_factory = \Passmarked\RequestFactory($config);
$psr7_request = $request_factory->create(['url' => 'http://www.github.com']);
// Then execute the request using your own client
```

## Main API Reference

* [Authentication](https://github.com/passmarked/passmarked/wiki/authentication)
* [create](https://github.com/passmarked/passmarked/wiki/create)
* [getReport](https://github.com/passmarked/passmarked/wiki/report)
* [getWebsites](https://github.com/passmarked/passmarked/wiki/websites)
* [getProfile](https://github.com/passmarked/passmarked/wiki/profile)
* [getBalance](https://github.com/passmarked/passmarked/wiki/balance)
* [createRunner](https://github.com/passmarked/passmarked/wiki/runner)

## Contributing

1. Fork the project
2. Write a test that reproduces the bug
3. Fix the bug without breaking any of the existing tests
4. Submit a pull request

We're busy building the tests and refactoring code as we go. If you spot any area that could use help feel free to open a PR.

## License

Copyright 2016 Passmarked Inc

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
