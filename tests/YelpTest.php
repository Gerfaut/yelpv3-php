<?php

namespace Gerfaut\Yelp\Test;

use Gerfaut\Yelp\Exception\ApiException;
use Gerfaut\Yelp\Model\AutocompleteResponse;
use Gerfaut\Yelp\Model\Business;
use Gerfaut\Yelp\Model\BusinessesResponse;
use Gerfaut\Yelp\Model\ReviewsResponse;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\SerializerBuilder;
use Mockery as m;
use Psr\Http\Message\ResponseInterface;
use Gerfaut\Yelp\Client as Yelp;
use Doctrine\Common\Annotations\AnnotationRegistry;

//Needed to load JMS/Serializer annotations
$loader = require __DIR__.'/../vendor/autoload.php';
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

class YelpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Yelp
     */
    private $client;

    public function setUp()
    {
        $this->client = new Yelp([
            'consumerKey' =>       getenv('YELP_CONSUMER_KEY'),
            'consumerSecret' =>    getenv('YELP_CONSUMER_SECRET'),
            'apiHost' =>           'api.yelp.com'
        ]);
    }

    protected function getResponseJson($type)
    {
        return file_get_contents(__DIR__.'/'.$type.'_response.json');
    }

    protected function getHttpClient($path, $status = 200, $payload = null)
    {
        $response = m::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->andReturn($payload);
        $tokenResponse = m::mock(ResponseInterface::class);
        $tokenResponse->shouldReceive('getBody')->andReturn($this->getResponseJson('token'));

        if ($status < 300) {
            $client = m::mock(HttpClient::class);

            //TODO make this work
            /*$client->shouldReceive('request')
                   ->with('POST', '/oauth2/', m::type('array'))
                   ->once()
                   ->andReturn($tokenResponse);

            $client->shouldReceive('request')
                   ->with('GET', m::on(function ($url) use ($path) {
                       return strpos($url, $path) > 0;
                   }), m::type('array'))
                   ->once()
                   ->andReturn($response);*/

            //First call is for token, second is for data
            $client->shouldReceive('request')->andReturn($tokenResponse, $response);
        } else {
            $mock = new MockHandler([
                new Response($status, [], $payload),
            ]);

            $handler = HandlerStack::create($mock);
            $client = new HttpClient(['handler' => $handler]);
        }

        return $client;
    }

    public function testConfigurationMapper()
    {
        $config = [
            'consumer_key' =>       uniqid(),
            'consumer_secret' =>    uniqid(),
            'api_host' =>           uniqid()
        ];

        $client = new Yelp($config);

        //Reflection to test non public values
        $reflection = new \ReflectionClass($client);
        $consumerKeyProperty = $reflection->getProperty('consumerKey');
        $consumerKeyProperty->setAccessible(true);
        $consumerSecretProperty = $reflection->getProperty('consumerSecret');
        $consumerSecretProperty->setAccessible(true);
        $apiHostProperty = $reflection->getProperty('apiHost');
        $apiHostProperty->setAccessible(true);


        $this->assertEquals($config['consumer_key'], $consumerKeyProperty->getValue($client));
        $this->assertEquals($config['consumer_secret'], $consumerSecretProperty->getValue($client));
        $this->assertEquals($config['api_host'], $apiHostProperty->getValue($client));
    }

    /**
     * @expectedException Gerfaut\Yelp\Exception\ApiException
     */
    public function test_It_Will_Fail_With_Invalid_Credentials()
    {
        $business_id = 'the-motel-bar-chicago';
        $path = '/v3/business/'.urlencode($business_id);
        $response = $this->getResponseJson('error');
        $httpClient = $this->getHttpClient($path, 401, $response);

        $business = $this->client->setHttpClient($httpClient)->getBusiness($business_id);
    }

    /**
     * @expectedException Gerfaut\Yelp\Exception\DeserializeException
     */
    public function test_It_Will_Fail_With_Invalid_Json_Response_Format()
    {
        $business_id = 'the-motel-bar-chicago';
        $path = '/v3/business/'.urlencode($business_id);
        $response = $this->getResponseJson('format_error');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $business = $this->client->setHttpClient($httpClient)->getBusiness($business_id);
    }

    public function test_Exceptions_From_Http_Contain_Response_Body()
    {
        $business_id = 'the-motel-bar-chicago';
        $path = '/v3/business/'.urlencode($business_id);
        $response = $this->getResponseJson('error');
        $httpClient = $this->getHttpClient($path, 401, $response);

        try {
            $business = $this->client->setHttpClient($httpClient)->getBusiness($business_id);
        } catch (ApiException $e) {
            $this->assertNotNull($e->getResponseBody());
        }
    }

    public function test_It_Can_Find_Business_By_Id()
    {
        $business_id = 'urban-curry-san-francisco';
        $path = '/v3/business/'.urlencode($business_id);
        $response = $this->getResponseJson('business');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $business = $this->client->setHttpClient($httpClient)->getBusiness($business_id);

        $this->assertInstanceOf(Business::class, $business);
        $this->assertEquals($business_id, $business->getId());
    }
    
    public function test_It_Can_Search_Bars_In_Chicago()
    {
        $term = 'bars';
        $location = 'Chicago, IL';
        $attributes = ['term' => $term, 'location' => $location];

        //Reflection to use protected method
        $reflection = new \ReflectionClass($this->client);
        $buildQueryParamsMethod = $reflection->getMethod('buildQueryParamsForSearch');
        $buildQueryParamsMethod->setAccessible(true);
        $result = $buildQueryParamsMethod->invokeArgs($this->client, array($attributes));

        $path = '/v3/search/?'.$result;
        $response = $this->getResponseJson('search');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $results = $this->client->setHttpClient($httpClient)->search($attributes);

        $this->assertInstanceOf(BusinessesResponse::class, $results);
        $this->assertNotEmpty($results->getBusinesses());
        $this->assertEquals(1, count($results->getBusinesses()));
    }

    public function test_It_Can_Search_By_Phone()
    {
        $phone = '(312) 822-2900';
        $attributes = ['phone' => $phone];
        $path = '/v3/phone_search/?'.http_build_query($attributes);
        $response = $this->getResponseJson('search');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $results = $this->client->setHttpClient($httpClient)->searchByPhone($attributes);

        $this->assertInstanceOf(BusinessesResponse::class, $results);
        $this->assertNotEmpty($results->getBusinesses());
        $this->assertEquals(1, count($results->getBusinesses()));
    }

    public function test_It_Can_Set_Defaults()
    {
        $default_term = 'stores';
        $default_location = 'Chicago, IL';
        $default_limit = 10;
        $attributes = ['term' => $default_term, 'location' => $default_location, 'limit' => $default_limit];


        //Reflection to use protected method
        $reflection = new \ReflectionClass($this->client);
        $buildQueryParamsMethod = $reflection->getMethod('buildQueryParamsForSearch');
        $buildQueryParamsMethod->setAccessible(true);
        $result = $buildQueryParamsMethod->invokeArgs($this->client, array($attributes));

        $path = '/v3/search/?'.$result;
        $response = $this->getResponseJson('search');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $results = $this->client->setDefaultLocation($default_location)
            ->setDefaultTerm($default_term)
            ->setSearchLimit($default_limit)
            ->setHttpClient($httpClient)
            ->search();

        $this->assertInstanceOf(BusinessesResponse::class, $results);
        $this->assertNotEmpty($results->getBusinesses());
        $this->assertEquals(1, count($results->getBusinesses()));
    }

    public function test_It_Can_Find_Business_By_Id_With_Special_Characters()
    {
        $business_id = 'xware42-münchen-3';
        $path = '/v3/business/'.urlencode($business_id);
        $response = $this->getResponseJson('business');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $business = $this->client->setHttpClient($httpClient)->getBusiness($business_id);

        $this->assertInstanceOf(Business::class, $business);
        $this->assertNotNull($business->getId());
    }

    public function test_It_Can_Retrieve_Autocomplete_Suggestions()
    {
        $attributes = ['text' => 'park', 'latitude' => 37.786942, 'longitude' => -122.399643];

        //Reflection to use protected method
        $reflection = new \ReflectionClass($this->client);
        $buildQueryParamsMethod = $reflection->getMethod('buildQueryParamsForSearch');
        $buildQueryParamsMethod->setAccessible(true);
        $result = $buildQueryParamsMethod->invokeArgs($this->client, array($attributes));

        $path = '/v3/autocomplete/?'.$result;
        $response = $this->getResponseJson('autocomplete');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $autocomplete = $this->client->setHttpClient($httpClient)->getAutocompleteSuggestions($attributes);

        $this->assertInstanceOf(AutocompleteResponse::class, $autocomplete);
        $this->assertEquals(1, count($autocomplete->getBusinesses()));
        $this->assertEquals(3, count($autocomplete->getCategories()));
        $this->assertEquals(3, count($autocomplete->getTerms()));
    }

    public function test_It_Can_Find_Transactions()
    {
        $attributes = ['latitude' => 37.786942, 'longitude' => -122.399643];

        //Reflection to use protected method
        $reflection = new \ReflectionClass($this->client);
        $buildQueryParamsMethod = $reflection->getMethod('buildQueryParamsForSearch');
        $buildQueryParamsMethod->setAccessible(true);
        $result = $buildQueryParamsMethod->invokeArgs($this->client, array($attributes));

        $path = '/v3/transactions/delivery/search?'.$result;
        $response = $this->getResponseJson('transactions');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $transactions = $this->client->setHttpClient($httpClient)->getTransactions($attributes);

        $this->assertInstanceOf(BusinessesResponse::class, $transactions);
        $this->assertEquals(117, $transactions->getTotal());
        $this->assertNotEquals(0, count($transactions->getBusinesses()));
    }

    public function test_It_Can_Find_Transactions_With_Custom_Transaction_Type()
    {
        $attributes = ['latitude' => 37.786942, 'longitude' => -122.399643, 'transaction_type' => 'delivery'];

        //Reflection to use protected method
        $reflection = new \ReflectionClass($this->client);
        $buildQueryParamsMethod = $reflection->getMethod('buildQueryParamsForSearch');
        $buildQueryParamsMethod->setAccessible(true);
        $result = $buildQueryParamsMethod->invokeArgs($this->client, array($attributes));

        $path = '/v3/transactions/delivery/search?'.$result;
        $response = $this->getResponseJson('transactions');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $transactions = $this->client->setHttpClient($httpClient)->getTransactions($attributes);

        $this->assertInstanceOf(BusinessesResponse::class, $transactions);
        $this->assertEquals(117, $transactions->getTotal());
        $this->assertNotEquals(0, count($transactions->getBusinesses()));
    }

    public function test_It_Can_Find_Reviews()
    {
        $business_id = 'north-india-restaurant-san-francisco';
        $path = '/v3/businesses/'.urlencode($business_id).'/reviews';
        $response = $this->getResponseJson('reviews');
        $httpClient = $this->getHttpClient($path, 200, $response);

        $reviews = $this->client->setHttpClient($httpClient)->getReviews($business_id);

        $this->assertInstanceOf(ReviewsResponse::class, $reviews);
        $this->assertEquals(814, $reviews->getTotal());
        $this->assertNotEquals(0, count($reviews->getReviews()));
    }
}
