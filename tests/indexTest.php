<?php
require_once __DIR__.'/../vendor/autoload.php';

use Silex\WebTestCase;
class IndexTest extends WebTestCase
{

    public function createApplication()
    {
        require __DIR__.'/../index.php';
        $app['debug'] = true;
        unset($app['exception_handler']);        
    	return $app;
    }


    public function testBasicHeaderResponse()
    {
        $client = $this->createClient();
    	$crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isOk());
    }

    
    public function testBasic()
    {
        $client = $this->createClient();
    	$crawler = $client->request('GET', '/');
    	$this->assertEquals('Try /hello/:name', $client->getResponse()->getContent());
    }
  
    
    
    public function testGreeting()
    {
        $client = $this->createClient();
    	$crawler = $client->request('GET', '/hello/daniel');
    	$this->assertEquals('Hello daniel', $client->getResponse()->getContent());
    }

    
    public function testTweets()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/histogram/Daniel76433021');                    
        //$this->assertEquals('{"18":1}', $client->getResponse()->getContent());
        $this->assertJsonStringEqualsJsonString(json_encode(["18" => 1]),$client->getResponse()->getContent());
    }
}
