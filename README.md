
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
composer require passmarked/php
```
N.B Not yet available
View the project at [packagist.org/packages/passmarked/php](https://packagist.org/packages/passmarked/php).

### From Source

To build from source:

```bash
git clone git@github.com:passmarked/php.git passmarked-php/
cd passmarked-php/
composer install
```
e module can also be used as a regular module that allows programs to integrate with the Passmarked system.

## API

* [Authentication](https://github.com/passmarked/passmarked/wiki/authentication)
* [create](https://github.com/passmarked/passmarked/wiki/create)
* [getReport](https://github.com/passmarked/passmarked/wiki/report)
* [getWebsites](https://github.com/passmarked/passmarked/wiki/websites)
* [getProfile](https://github.com/passmarked/passmarked/wiki/profile)
* [getBalance](https://github.com/passmarked/passmarked/wiki/balance)
* [createRunner](https://github.com/passmarked/passmarked/wiki/runner)

## Usage Examples

### Run a report
```php
$config = [
    'api_token'     => 'YOUR_PASSMARKED_API_TOKEN', // Get one on passmarked.com
];
$client = new \Passmarked\Client($config);

$report = $client->create(['url' => 'http://www.github.com']);

echo "Response Status: {$report->status}";
echo "UID: {$report->uid}";
// or
echo "Response Status: ".$report->get('status');
echo "UID: ".$report->get('uid');
```

### Getting the PSR-7 Response
```php
$config = [
    'api_token'     => 'YOUR_PASSMARKED_API_TOKEN', // Get one on passmarked.com
];
$client = new \Passmarked\Client($config);

$response = $client->create(['url' => 'http://www.github.com'])->getPsr7Response();

echo $response->getBody()->getContents();
```

### Generating Psr7 Requests
```php
$config = [
    'api_token'     => 'YOUR_PASSMARKED_API_TOKEN', // Get one on passmarked.com
];
$request_factory = \Passmarked\RequestFactory($config);
$psr7_request = $request_factory->create(['url' => 'http://www.github.com']);
// Then execute the request using your own client
```

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