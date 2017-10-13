<?php

/**
 * Passmarked\ClientTest
 *
 * Tests for Passmarked\Client
 *
 * PHP version 5.6
 *
 * Copyright 2016 Passmarked Inc
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package    Passmarked
 * @author     Werner Roets <cobolt.exe@gmail.com>
 * @copyright  2016 Passmarked Inc
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link       http://packagist.org/packages/passmarked/php
 */

namespace Passmarked\Tests;

use PHPUnit\Framework\TestCase;
use Passmarked\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ClientTest extends TestCase
{
    private $config = [
        'api_url' => 'https://api.passmarked.com',
        'api_version' => '2',
        'api_token' => 'a76e916065eb11e6acc397d58e05fff63551471597348214',
        'http_version' => '1.1',
        'telemetry' => true,
        'other' => 'other'
    ];

    private $test_client;

    public function __construct()
    {
        parent::__construct();
        $responses = [
            new Response(200, ['X-Foo' => 'Bar'], '{ "status" : "ok" }'),
            new Response(202, ['Content-Length' => 0]),
        ];
        // Make a test client
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $this->config['handler'] = $handler;
        $this->test_client = new Client($this->config);

    }

    public function testConstructAllConfig()
    {
        $client = new \Passmarked\Client($this->config);
        $this->assertInstanceof('Passmarked\\Client', $client);
    }

    public function testConstructEmptyConfig()
    {
        $client = new \Passmarked\Client([]);
        $this->assertInstanceof('Passmarked\\Client', $client);
    }

    public function testMock()
    {
        $websites = $this->test_client->getWebsites();
    }

}