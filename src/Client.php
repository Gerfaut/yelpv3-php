<?php

namespace Gerfaut\Yelp;

use Gerfaut\Yelp\Exception\ApiException;
use Gerfaut\Yelp\Exception\DeserializeException;
use Gerfaut\Yelp\Model\AutocompleteResponse;
use Gerfaut\Yelp\Model\Business;
use Gerfaut\Yelp\Model\BusinessesResponse;
use Gerfaut\Yelp\Model\ReviewsResponse;
use GuzzleHttp\Exception\ClientException;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use GuzzleHttp\Client as HttpClient;

/**
 * Class Client
 * @package Gerfaut\Yelp
 */
class Client
{
    /**
     * Search path
     *
     * @var string
     */
    protected $searchPath = '/v3/businesses/search';

    /**
     * Business path
     *
     * @var string
     */
    protected $businessPath = '/v3/businesses/%s';

    /**
     * Phone search path
     *
     * @var string
     */
    protected $phoneSearchPath = '/v3/businesses/search/phone';

    /**
     * Reviews path
     *
     * @var string
     */
    protected $reviewsPath = '/v3/businesses/%s/reviews';

    /**
     * Transactions path
     *
     * @var string
     */
    protected $transactionsPath = '/v3/transactions/%s/search';

    /**
     * Autocomplete path
     *
     * @var string
     */
    protected $autocompletePath = '/v3/autocomplete';

    /**
     * Oauth2 token path
     *
     * @var string
     */
    protected $oauth2TokenPath = '/oauth2/token';

    /**
     * Default search term
     *
     * @var string
     */
    protected $defaultTerm = 'restaurants';

    /**
     * Default location
     *
     * @var string
     */
    protected $defaultLocation = 'USA';

    /**
     * Default search limit
     *
     * @var integer
     */
    protected $searchLimit = 10;

    /**
     * API host url
     *
     * @var string
     */
    protected $apiHost;

    /**
     * Consumer key
     *
     * @var string
     */
    protected $consumerKey;

    /**
     * Consumer secret
     *
     * @var string
     */
    protected $consumerSecret;

    /**
     * Access token
     *
     * @var string
     */
    protected $accessToken;

    /**
     * HttpClient used to query the API
     *
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * Client constructor.
     * @param array $configuration
     */
    public function __construct($configuration = [])
    {
        $this->parseConfiguration($configuration)
            ->createHttpClient();

        $this->serializer = SerializerBuilder::create()->build();
    }


    /**
     * Get autocomplete suggestions
     *
     * @param array $attributes
     *
     * @return AutocompleteResponse The response object from the request
     */
    public function getAutocompleteSuggestions($attributes = [])
    {
        $path = $this->autocompletePath."?".$this->prepareQueryParams($attributes);

        return $this->request($path, Model\AutocompleteResponse::class);
    }

    /**
     * Query the Business API by business id
     *
     * @param    string $businessId The ID of the business to query
     *
     * @return   Business The response object from the request
     */
    public function getBusiness($businessId)
    {
        $businessPath = sprintf($this->businessPath, urlencode($businessId));

        return $this->request($businessPath, Model\Business::class);
    }

    /**
     * Get reviews for business by ID string
     *
     * @param string $businessId
     *
     * @return ReviewsResponse The response object from the request
     */
    public function getReviews($businessId)
    {
        $path = sprintf($this->reviewsPath, urlencode($businessId));

        return $this->request($path, Model\ReviewsResponse::class);
    }

    /**
     * Get transactions - food delivery in the US
     *
     * @param array $attributes
     *
     * @return BusinessesResponse  The response object from the request
     */
    public function getTransactions($attributes = [])
    {
        // default transaction type
        $transactionType = 'delivery';

        if (isset($attributes['transaction_type'])) {
            $transactionType = $attributes['transaction_type'];
            unset($attributes['transaction_type']);
        }

        $path = $this->prepareQueryParams($attributes);

        $path = sprintf($this->transactionsPath, urlencode($transactionType))."?".$path;

        return $this->request($path, Model\BusinessesResponse::class);
    }

    /**
     * Query the Search API by a search term and location
     *
     * @param    array $attributes Query attributes
     *
     * @return   BusinessesResponse The response object from the request
     */
    public function search($attributes = [])
    {
        $queryString = $this->buildQueryParamsForSearch($attributes);
        $searchPath = $this->searchPath."?".$queryString;

        return $this->request($searchPath, Model\BusinessesResponse::class);
    }

    /**
     * Search for businesses by phone number
     *
     * @param string $phone
     *
     * @return BusinessesResponse The response object from the request
     */
    public function searchByPhone($phone)
    {
        $path = $this->phoneSearchPath."?".$this->prepareQueryParams(['phone' => $phone]);

        return $this->request($path, Model\BusinessesResponse::class);
    }

    /**
     * Set default location
     *
     * @param string $location
     *
     * @return Client
     */
    public function setDefaultLocation($location)
    {
        $this->defaultLocation = $location;

        return $this;
    }
    /**
     * Set default term
     *
     * @param string $term
     *
     * @return Client
     */
    public function setDefaultTerm($term)
    {
        $this->defaultTerm = $term;

        return $this;
    }

    /**
     * Set search limit
     *
     * @param integer $limit
     *
     * @return Client
     */
    public function setSearchLimit($limit)
    {
        if (is_int($limit)) {
            $this->searchLimit = $limit;
        }

        return $this;
    }

    /**
     * Updates the yelp client's http client to the given http client. Client.
     *
     * @param HttpClient $client
     *
     * @return  Client
     */
    public function setHttpClient(HttpClient $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Get new access token from Yelp API.
     * @return Client
     * @throws ApiException
     */
    protected function setAccessToken()
    {
        try {
            // try to get access token from API
            $auth2Response = $this->httpClient->request(
                'POST',
                'https://'.$this->apiHost.$this->oauth2TokenPath,
                [
                    'query' => [
                        'grant_type' => 'client_credentials',
                        'client_id' => $this->consumerKey,
                        'client_secret' => $this->consumerSecret,
                    ]
                ]
            );
        } catch (ClientException $e) {
            $exception = new ApiException($e->getMessage());
            throw $exception->setResponseBody($e->getResponse()->getBody());
        }

        $decodedArr = json_decode($auth2Response->getBody(), true);

        if (isset($decodedArr['access_token'])) {
            $this->accessToken = $decodedArr['access_token'];
        }

        return $this;
    }

    /**
     * Build query string params using defaults for search() functionality
     *
     * @param  array $attributes
     *
     * @return string
     */
    protected function buildQueryParamsForSearch($attributes = [])
    {
        $defaults = array(
            'term' => $this->defaultTerm,
            'location' => $this->defaultLocation,
            'limit' => $this->searchLimit
        );

        $attributes = array_merge($defaults, $attributes);

        return $this->prepareQueryParams($attributes);
    }

    /**
     * Build unsigned url
     *
     * @param  string $host
     * @param  string $path
     *
     * @return string   Unsigned url
     */
    protected function buildUnsignedUrl($host, $path)
    {
        return "https://".$host.$path;
    }

    /**
     * Builds and sets a preferred http client.
     *
     * @return Client
     */
    protected function createHttpClient()
    {
        $client = new HttpClient();

        return $this->setHttpClient($client);
    }

    /**
     * Maps legacy configuration keys to updated keys.
     *
     * @param  array $configuration
     *
     * @return array
     */
    protected function mapConfiguration(array $configuration)
    {
        array_walk($configuration, function ($value, $key) use (&$configuration) {
            $newKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            $configuration[$newKey] = $value;
        });

        return $configuration;
    }

    /**
     * Parse configuration using defaults
     *
     * @param  array $configuration
     *
     * @return client
     */
    protected function parseConfiguration($configuration = [])
    {
        $defaults = array(
            'consumerKey' => null,
            'consumerSecret' => null,
            'apiHost' => 'api.yelp.com'
        );
        $configuration = array_merge($defaults, $this->mapConfiguration($configuration));
        array_walk($configuration, [$this, 'setConfig']);

        return $this;
    }

    /**
     * Attempts to set a given value.
     *
     * @param mixed   $value
     * @param string  $key
     *
     * @return Client
     */
    protected function setConfig($value, $key)
    {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Updates query params array to apply yelp specific formatting rules.
     *
     * @param  array $params
     *
     * @return string
     */
    protected function prepareQueryParams($params = [])
    {
        array_walk($params, function ($value, $key) use (&$params) {
            if (is_bool($value)) {
                $params[$key] = $value ? 'true' : 'false';
            }
        });

        return http_build_query($params);
    }

    /**
     * Makes a request to the Yelp API and returns the response
     *
     * @param    string $path The path of the APi after the domain
     * @param    string $fullClassName The full qualified name of the class to be used to deserialize the response
     * @return stdClass The JSON response from the request
     * @throws DeserializeException
     * @throws  ApiException
     */
    protected function request($path, $fullClassName)
    {
        if (empty($this->accessToken)) {
            $this->setAccessToken();
        }

        $url = $this->buildUnsignedUrl($this->apiHost, $path);

        //try {
            $response = $this->httpClient->request(
                'get',
                $url,
                ['headers' =>
                    ['Authorization' => "Bearer {$this->accessToken}"]
                ]
            );
        //} catch (\Exception $e) {
        //    $exception = new ApiException($e->getMessage());
        //    throw $exception->setResponseBody($e->getResponse()->getBody());
        //}

        try {
            return $this->serializer->deserialize($response->getBody(), $fullClassName, 'json');
        } catch (\Exception $e) {
            throw new DeserializeException("Error in deserialization (".$response->getBody().
                                                    " ----> ".$fullClassName.")", -1, $e);
        }
    }
}
