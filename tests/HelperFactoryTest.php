<?php

/**
 * Passmarked\HelperFactoryTest
 *
 * Tests for Passmarked\HelperFactory
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
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Passmarked\HelperFactory;

class HelperFactoryTest extends TestCase {
    
    public function testCanConstruct() {
        $helper_factory = new HelperFactory();
        $this->assertInstanceof('Passmarked\\HelperFactory',$helper_factory);
        
    }

    private function getMethodNameFromMockResponseFilename($absolute_filename) {
         $filename = explode('/',$absolute_filename);
         return preg_replace("/\d+$/",'',explode('.',end($filename))[0]);
    }

    public function testMakeHelper() {
        $helper_factory = new HelperFactory();

        $absolute_filenames = glob(dirname(__FILE__).'/responses/200/*.json');
        foreach( $absolute_filenames as $absolute_filename ) {
            $method_name = $this->getMethodNameFromMockResponseFilename($absolute_filename);
            $mock_response_body = file_get_contents($absolute_filename);
            $helper = $helper_factory->$method_name(
            new \GuzzleHttp\Psr7\Response(
                200,
                ['X-PASSMARKED'],        
                $mock_response_body,        
                '1.1',
                'OK'
            ));
            $this->assertInstanceof('Passmarked\\Helper\\Helper',$helper);
        }
    }

    /**
     * @expectedException Passmarked\Exception\ApiErrorException
     */
    public function test401ErrorInvalidToken() {
        $helper_factory = new HelperFactory();
        $mock_response_body = file_get_contents(dirname(__FILE__).'/responses/401/API_AUTH_FAILED.json');
        $method_name = "generic";
        $helper = $helper_factory->$method_name(
            new \GuzzleHttp\Psr7\Response(
                401,
                ['X-PASSMARKED'],        
                $mock_response_body,        
                '1.1'
            ));
        // $this->assertObjectHasAttribute('api_error_code', $e);
    }

    /**
     * @expectedException Passmarked\Exception\ApiErrorException
     */
    public function test401ErrorTokenFailed() {
        $helper_factory = new HelperFactory();
        $mock_response_body = file_get_contents(dirname(__FILE__).'/responses/401/TOKEN_FAILED.json');
        $method_name = "generic";
        $helper = $helper_factory->$method_name(
            new \GuzzleHttp\Psr7\Response(
                401,
                ['X-PASSMARKED'],        
                $mock_response_body,        
                '1.1'
            ));
    }

    /**
     * @expectedException Passmarked\Exception\ApiErrorException
     */
    public function test404ErrorNotFound() {
        $helper_factory = new HelperFactory();
        $mock_response_body = file_get_contents(dirname(__FILE__).'/responses/404/NOT_FOUND.json');
        $method_name = "generic";
        $helper = $helper_factory->$method_name(
            new \GuzzleHttp\Psr7\Response(
                404,
                ['X-PASSMARKED'],        
                $mock_response_body,        
                '1.1'
            ));
    }
}