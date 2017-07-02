# DEPRECATED - Yelp PHP Client


# DEPRECATED
The base project of this fork is now officially supporting YELP V3 Fusion : [https://github.com/stevenmaguire/yelp-php](https://github.com/stevenmaguire/yelp-php). 

Use that one instead.


----- 


[![Latest Version](https://img.shields.io/github/release/gerfaut/yelpv3-php.svg?style=flat-square)](https://github.com/gerfaut/yelpv3-php/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/Gerfaut/yelpv3-php/master.svg?style=flat-square&1)](https://travis-ci.org/Gerfaut/yelpv3-php)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/gerfaut/yelpv3-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/gerfaut/yelpv3-php/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/gerfaut/yelpv3-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/gerfaut/yelpv3-php)
[![Total Downloads](https://img.shields.io/packagist/dt/gerfaut/yelpv3-php.svg?style=flat-square)](https://packagist.org/packages/gerfaut/yelpv3-php)

A PHP client for authenticating with Yelp using OAuth2 and consuming the Fusion API (v3).

More information about [Yelp Fusion (v3) API](https://www.yelp.com/developers/documentation/v3/get_started).

## Install

Via Composer

``` bash
$ composer require gerfaut/yelpv3-php
```

## Usage

### Create client

```php
    $client = new Gerfaut\Yelp\Client(array(
        'consumerKey' => 'YOUR COSUMER KEY',
        'consumerSecret' => 'YOUR CONSUMER SECRET',
        'apiHost' => 'api.yelp.com' // Optional, default 'api.yelp.com'
    ));
```

### Search by keyword and location
Yelp documentation and available parameters : https://www.yelp.com/developers/documentation/v3/business_search

```php
$results = $client->search(array('term' => 'Sushi', 'location' => 'Chicago, IL'));
```

### Search by phone number

Yelp documentation : https://www.yelp.com/developers/documentation/v3/business_search_phone

```php
$results = $client->searchByPhone(array('phone' => '867-5309'));
```

### Locate details for a specific business by Yelp business id

Yelp documentation : https://www.yelp.com/developers/documentation/v3/business

```php
$results = $client->getBusiness('union-chicago-3');
```

### Get reviews for a specific business by Yelp business id

Yelp documentation : https://www.yelp.com/developers/documentation/v3/business_reviews

```php
$results = $client->getReviews('union-chicago-3');
```

### Retrieve autocomplete suggestions for keywords, businesses and categories, based on the input text

Yelp documentation : https://www.yelp.com/developers/documentation/v3/autocomplete

```php
$results = $client->getAutocompleteSuggestions(array('text' => 'park', 'latitude' => 37.786942, 'longitude' => -122.399643));
```
### Retrieve list of businesses which support certain transactions.

Yelp documentation : https://www.yelp.com/developers/documentation/v3/transactions_search

```php
$results = $client->getTransactions(array('transaction_type' => 'delivery', 'latitude' => 37.786942, 'longitude' => -122.399643));
```
Currently 'delivery' is the only transaction type supported by Yelp and is the default value.
```php
$results = $client->getTransactions(array('latitude' => 37.786942, 'longitude' => -122.399643));
```

### Configure defaults

```php
$client->setDefaultLocation('Chicago, IL')  // default location for all searches if location not provided
    ->setDefaultTerm('Sushi')               // default keyword for all searches if term not provided
    ->setSearchLimit(20);                   // number of records to return
```

### Exceptions

If the API request results in an Http error, the client will throw a `Gerfaut\Yelp\Exception\ApiException` that includes the response body, as a string, from the Yelp API.
If the Deserialization process results in an error, the client will throw a `Gerfaut\Yelp\Exception\DeserializeException` that includes the Exception from JMS\Serialize librabry.

```php
$responseBody = $e->getResponseBody(); // string from Http request
$responseBodyObject = json_decode($responseBody);
```

## Testing

Using your global phpunit install:

``` bash
$ phpunit
```

Or using the vendors (from the root folder):
``` bash
$ ./vendor/phpunit/phpunit/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Christophe Leemans](https://github.com/Gerfaut)
- [Steven Maguire](https://github.com/stevenmaguire) for his [v2 library](https://github.com/stevenmaguire/yelp-php)
- [compworkmail](https://github.com/compworkmail) for his very nice start of [v3 implementation](https://github.com/compworkmail/yelp-v3-php-API-symfony)
- [All Contributors](https://github.com/gerfaut/yelpv3-php/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
