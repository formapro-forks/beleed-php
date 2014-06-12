<?php
namespace Beleed\Client\Tests\Functional;

use Beleed\Client\Client;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Organization;
use Beleed\Client\Model\Product;
use Buzz\Client\Curl;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected static $client;

    public static function setUpBeforeClass()
    {
        if (false == isset($GLOBALS['BELEED_ACCESS_TOKEN'])) {
            throw new \PHPUnit_Framework_SkippedTestError('These tests require BELEED_ACCESS_TOKEN to be set in phpunit.xml');
        }

        $curl = new Curl;
        $curl->setTimeout(10);

        self::$client = new Client($curl, $GLOBALS['BELEED_ACCESS_TOKEN']);
    }

    public function testCreateProduct()
    {
        $product = new Product;
        $product->name = 'test';
        $product->price = '1231';

        $actualProduct = self::$client->createProduct($product);

        $this->assertSame($product, $actualProduct);

        $this->assertNotEmpty($product->id);
        $this->assertNotEmpty($product->name);
        $this->assertNotEmpty($product->price);

        return $product;
    }

    /**
     * @depends testCreateProduct
     */
    public function testFetchProduct(Product $product)
    {
        $actualProduct = self::$client->fetchProduct($product->id);

        $this->assertEquals($product->id, $actualProduct->id);
        $this->assertEquals($product->name, $actualProduct->name);
        $this->assertEquals($product->price, $actualProduct->price);
    }

    public function testCreateOrganization()
    {
        $organization = new Organization;
        $organization->name = 'theOrgName'.time().uniqid();
        $organization->description = 'theOrgDesc';
        $organization->url = 'http://theorg.url';

        $actualOrganization = self::$client->createOrganization($organization);

        $this->assertSame($organization, $actualOrganization);

        $this->assertNotEmpty($organization->id);
        $this->assertNotEmpty($organization->name);
        $this->assertNotEmpty($organization->url);
        $this->assertNotEmpty($organization->description);

        return $organization;
    }

    /**
     * @depends testCreateOrganization
     */
    public function testFetchOrganization(Organization $organization)
    {
        $actualOrganization = self::$client->fetchOrganization($organization->id);

        $this->assertEquals($organization->id, $actualOrganization->id);
        $this->assertEquals($organization->name, $actualOrganization->name);
        $this->assertEquals($organization->description, $actualOrganization->description);
        $this->assertEquals($organization->url, $actualOrganization->url);
    }

    public function testCreateOpportunityWithNewOrganizationAndProduct()
    {
        $organization = new Organization;
        $organization->name = 'theOrgName'.time().uniqid();
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

        $actualOpportunity = self::$client->createOpportunity($opportunity);

        $this->assertSame($opportunity, $actualOpportunity);

        $this->assertNotEmpty($opportunity->id);
        $this->assertNotEmpty($opportunity->comment);
        $this->assertNotEmpty($opportunity->status);
        $this->assertNotEmpty($opportunity->confidence);
        $this->assertNotEmpty($opportunity->value);

        $this->assertSame($organization, $opportunity->organization);
        $this->assertNotEmpty($organization->id);

        $this->assertSame($product, $opportunity->product);
        $this->assertNotEmpty($product->id);

        return $opportunity;
    }

    /**
     * @depends testCreateProduct
     * @depends testCreateOrganization
     */
    public function testCreateOpportunityWithExistOrganizationAndProduct(Product $product, Organization $organization)
    {
        $opportunity = new Opportunity;
        $opportunity->comment = 'aComment';
        $opportunity->status = 'active';
        $opportunity->confidence = '50';
        $opportunity->value = '500';
        $opportunity->organization = $organization;
        $opportunity->product = $product;

        $actualOpportunity = self::$client->createOpportunity($opportunity);

        $this->assertSame($opportunity, $actualOpportunity);

        $this->assertNotEmpty($opportunity->id);
        $this->assertNotEmpty($opportunity->comment);
        $this->assertNotEmpty($opportunity->status);
        $this->assertNotEmpty($opportunity->confidence);
        $this->assertNotEmpty($opportunity->value);

        $this->assertSame($organization, $opportunity->organization);
        $this->assertNotEmpty($organization->id);

        $this->assertSame($product, $opportunity->product);
        $this->assertNotEmpty($product->id);

        return $opportunity;
    }

    /**
     * @depends testCreateProduct
     * @depends testCreateOrganization
     */
    public function testCreateOpportunityWithExistOrganizationAndProductUsingIdsOnly(Product $product, Organization $organization)
    {
        $opportunity = new Opportunity;
        $opportunity->comment = 'aComment';
        $opportunity->status = 'active';
        $opportunity->confidence = '50';
        $opportunity->value = '500';
        $opportunity->organization_id = $organization->id;
        $opportunity->product_id = $product->id;

        $actualOpportunity = self::$client->createOpportunity($opportunity);

        $this->assertSame($opportunity, $actualOpportunity);

        $this->assertNotEmpty($opportunity->id);
        $this->assertNotEmpty($opportunity->comment);
        $this->assertNotEmpty($opportunity->status);
        $this->assertNotEmpty($opportunity->confidence);
        $this->assertNotEmpty($opportunity->value);

        $this->assertSame($organization, $opportunity->organization);
        $this->assertNotEmpty($organization->id);

        $this->assertSame($product, $opportunity->product);
        $this->assertNotEmpty($product->id);

        return $opportunity;
    }

    /**
     * @depends testCreateOpportunityWithNewOrganizationAndProduct
     */
    public function testFetchOpportunity(Opportunity $opportunity)
    {
        $actualOpportunity = self::$client->fetchOpportunity($opportunity->id);

        $this->assertEquals($opportunity->id, $actualOpportunity->id);
        $this->assertEquals($opportunity->comment, $actualOpportunity->comment);

        $this->assertInstanceOf('Beleed\Client\Model\Product', $actualOpportunity->product);
        $this->assertEquals($opportunity->product->id, $actualOpportunity->product->id);

        $this->assertInstanceOf('Beleed\Client\Model\Organization', $actualOpportunity->organization);
        $this->assertEquals($opportunity->organization->id, $actualOpportunity->organization->id);
    }
}