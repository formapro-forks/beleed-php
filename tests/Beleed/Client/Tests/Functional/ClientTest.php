<?php
namespace Beleed\Client\Tests\Functional;

use Beleed\Client\Client;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Organization;
use Beleed\Client\Model\Product;
use Buzz\Client\Curl;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected static $accessToken;

    public static function setUpBeforeClass()
    {
        if (false == isset($GLOBALS['BELEED_ACCESS_TOKEN'])) {
            throw new \PHPUnit_Framework_SkippedTestError('These tests require BELEED_ACCESS_TOKEN to be set in phpunit.xml');
        }

        self::$accessToken = $GLOBALS['BELEED_ACCESS_TOKEN'];
    }

    public function testCreateOpportunity()
    {
        $organization = new Organization;
        $organization->name = 'theOrgName';
        $organization->description = 'theOrgDesc';
        $organization->url = 'http://theorg.url';

        $product = new Product;
        $product->name = 'theProdName';
        $product->price = '99';

        $opportunity = new Opportunity;
        $opportunity->comment = 'aComment';
        $opportunity->status = 'active';
        $opportunity->confidence = '50';
        $opportunity->value = '500';
        $opportunity->organization = $organization;
        $opportunity->product = $product;

        $curl = new Curl;
        $curl->setTimeout(10);

        $client = new Client($curl, self::$accessToken);

        var_dump($client->createOpportunity($opportunity));
    }

} 