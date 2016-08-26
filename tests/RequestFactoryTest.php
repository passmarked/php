<?php

/**
 * Passmarked\Tests\RequestFactoryTest
 *
 * Tests for Passmarked\RequestFactory
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
 * @author     Werner Roets <werner@io.co.za>
 * @copyright  2016 Passmarked Inc
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link       http://pear.php.net/package/PackageName
 */

namespace Passmarked\Tests;

use PHPUnit\Framework\TestCase;
use Passmarked\RequestFactory;

class RequestFactoryTest extends TestCase {

    private $config = [
        'api_url'      => 'https://api.passmarked.com',
        'api_version'   => '2',
        'api_token'     => '76ae916065eb11e6acc397d58e05fff63551471597348412',
        'http_version'  => '1.1',
        'telemetry'     => true
    ];

    public function testCanConstruct(){
        $request_factory = new RequestFactory($this->config);
        $this->assertInstanceof('Passmarked\\RequestFactory',$request_factory);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetWebsites() {
        $request_factory = new RequestFactory($this->config);
        $request = $request_factory->getWebsites();
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
        $this->assertArrayHasKey('Host',$request->getHeaders());
        $host = $request->getHeaders()['Host'];
        $this->assertEquals(
            $this->config['api_url'],
            'https://'.$host[0]
        );
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetWebsite() {
        $request_factory = new RequestFactory($this->config);
        $request = $request_factory->getWebsite('1','token');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetReport() {
        $request_factory = new RequestFactory($this->config);
        $request = $request_factory->getReport('1','token');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetBalance() {
        $request_factory = new RequestFactory($this->config);
        $request = $request_factory->getBalance('token');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testGetProfile() {
        $request_factory = new RequestFactory($this->config);
        $request = $request_factory->getProfile('token');
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
    }

    /**
     * @depends testCanConstruct
     */
    public function testCreate() {
        $request_factory = new RequestFactory($this->config);
        $request = $request_factory->create([
            'url'       => 'http://somesite.com',
            'token'     => 'myapitoken',
            'recursive' => false,
            'limit'     => 0,
            'bail'      => false,
            'level'     => 0,
            'patterns' => [],
        ]);
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
        $this->assertEquals(
            'url=http://somesite.com&token=myapitoken&recursive=false&limit=0&bail=false',
            $request->getBody()->getContents()            
        );
    }

    /**
     * @depends testCanConstruct
     */
    public function testCreateDefaultToken() {
        $request_factory = new RequestFactory($this->config);

        $request = $request_factory->create(['url' => 'http://somesite.com']);

        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
        $contents = $request->getBody()->getContents();
        $this->assertEquals(
            "url=http://somesite.com&token={$this->config['api_token']}",            
            $contents
        );
    }

    /**
     * @depends testCanConstruct
     */
    public function testCreateAllOptions() {
        $request_factory = new RequestFactory($this->config);

        $request = $request_factory->create([
            'url'       => 'http://somesite.com',
            'token'     => 'myapitoken',
            'recursive' => true,
            'limit'     => 5,
            'bail'      => true,
            'level'     => 3,
            'patterns' => ['(\w+)\t','\r\n(\w+)','\w\u0020\w'],
        ]);
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
        $this->assertEquals(
            'url=http://somesite.com&token=myapitoken&recursive=true&limit=5&bail=true&level=3&patterns[]=(\w+)\t&patterns[]=\r\n(\w+)&patterns[]=\w\u0020\w',
            $request->getBody()->getContents()            
        );
    }

    /**
     * @depends testCanConstruct
     */
    public function testCreateBadOptions() {
        $request_factory = new RequestFactory($this->config);

        $request = $request_factory->create([
            'url'       => 3833.33,
            'token'     => 333.12,
            'recursive' => 77,
            'limit'     => false,
            'bail'      => 234234.33,
            'level'     => 'random',
            'patterns' => [1,2,2],
        ]);
        $this->assertInstanceof('GuzzleHttp\\Psr7\\Request',$request);
        $this->assertEquals(
            'url=3833.33&token=333.12&recursive=true&limit=0&bail=true&level=random&patterns[]=1&patterns[]=2&patterns[]=2',
            $request->getBody()->getContents()            
        );
    }

}