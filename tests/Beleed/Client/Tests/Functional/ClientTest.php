<?php
namespace Beleed\Client\Tests\Functional;

use Beleed\Client\Client;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Contact;
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
        $product->name = 'test'.time().uniqid();
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

    public function testCreateContact()
    {
        $contact = new Contact;
        $contact->name = 'contactName'.time().uniqid();
        $contact->description = 'theOrgDesc';
        $contact->url = 'http://url.com';
        $contact->organization_name = 'theOrgName'.time().uniqid();

        $actualContact = self::$client->createContact($contact);

        $this->assertSame($contact, $actualContact);

        $this->assertNotEmpty($contact->id);
        $this->assertNotEmpty($contact->name);
        $this->assertNotEmpty($contact->url);
        $this->assertNotEmpty($contact->description);
        $this->assertNotEmpty($contact->organization_name);
        $this->assertNotEmpty($contact->shared);

        return $contact;
    }

    /**
     * @depends testCreateContact
     */
    public function testUpdateContact()
    {
        $contact = new Contact;
        $contact->name = 'contactName'.time().uniqid();
        $contact->description = 'theOrgDesc';
        $contact->url = 'http://url.com';
        $contact->organization_name = 'theOrgName'.time().uniqid();

        self::$client->createContact($contact);
        $contact->name = 'contactName.updated';
        self::$client->updateContact($contact);
        $actualContact = self::$client->fetchContact($contact->id);

        $this->assertSame($contact->name, $actualContact->name);
    }

    /**
     * @depends testCreateContact
     */
    public function testFetchContact(Contact $contact)
    {
        $actualContact = self::$client->fetchContact($contact->id);

        $this->assertEquals($contact->id, $actualContact->id);
        $this->assertEquals($contact->name, $actualContact->name);
        $this->assertEquals($contact->description, $actualContact->description);
        $this->assertEquals($contact->url, $actualContact->url);
        $this->assertEquals($contact->shared, $actualContact->shared);
        // $this->assertEquals($contact->organization_name, $actualContact->organization_name);
    }

    /**
     * @depends testCreateProduct
     * @depends testCreateContact
     */
    public function testCreateOpportunityWithNewContactAndProduct()
    {
        $contact = new Contact;
        $contact->name = 'theOrgName'.time().uniqid();
        $contact->description = 'theOrgDesc';
        $contact->url = 'http://url.com';

        $product = new Product;
        $product->name = 'theProdName';
        $product->price = '99';

        $opportunity = new Opportunity;
        $opportunity->comment = 'aComment';
        $opportunity->confidence = '50';
        $opportunity->value = '500';
        $opportunity->contact = $contact;
        $opportunity->product = $product;

        $actualOpportunity = self::$client->createOpportunity($opportunity);

        $this->assertSame($opportunity, $actualOpportunity);

        $this->assertNotEmpty($opportunity->id);
        $this->assertNotEmpty($opportunity->comment);
        $this->assertEquals($opportunity->status, 0);
        $this->assertNotEmpty($opportunity->confidence);
        $this->assertNotEmpty($opportunity->value);

        // $this->assertSame($contact, $opportunity->contact);
        $this->assertNotEmpty($opportunity->contact->id);

        // $this->assertSame($product, $opportunity->product);
        $this->assertNotEmpty($opportunity->product->id);

        return $opportunity;
    }

    /**
     * @depends testCreateProduct
     * @depends testCreateContact
     */
    public function testCreateOpportunityWithExistContactAndProduct(Product $product, Contact $contact)
    {
        $opportunity = new Opportunity;
        $opportunity->comment = 'aComment';
        $opportunity->confidence = '50';
        $opportunity->value = '500';
        $opportunity->contact = $contact;
        $opportunity->product = $product;

        $actualOpportunity = self::$client->createOpportunity($opportunity);

        $this->assertSame($opportunity, $actualOpportunity);

        $this->assertNotEmpty($opportunity->id);
        $this->assertNotEmpty($opportunity->comment);
        $this->assertEquals($opportunity->status, 0);
        $this->assertNotEmpty($opportunity->confidence);
        $this->assertNotEmpty($opportunity->value);

        // $this->assertSame($contact, $opportunity->contact);
        $this->assertNotEmpty($contact->id);

        // $this->assertSame($product, $opportunity->product);
        $this->assertNotEmpty($product->id);

        return $opportunity;
    }

    /**
     * @depends testCreateProduct
     * @depends testCreateContact
     */
    public function testCreateOpportunityWithExistContactProductUsingIdsOnly(Product $product, Contact $contact)
    {
        $opportunity = new Opportunity;
        $opportunity->comment = 'aComment';
        $opportunity->confidence = '50';
        $opportunity->value = '500';
        $opportunity->contact_id = $contact->id;
        $opportunity->product_id = $product->id;

        $actualOpportunity = self::$client->createOpportunity($opportunity);

        $this->assertSame($opportunity, $actualOpportunity);

        $this->assertNotEmpty($opportunity->id);
        $this->assertNotEmpty($opportunity->comment);
        $this->assertEquals($opportunity->status, 0);
        $this->assertNotEmpty($opportunity->confidence);
        $this->assertNotEmpty($opportunity->value);

        // $this->assertSame($contact, $opportunity->contact);
        $this->assertNotEmpty($contact->id);

        // $this->assertSame($product, $opportunity->product);
        $this->assertNotEmpty($product->id);

        return $opportunity;
    }

    /**
     * @depends testCreateOpportunityWithNewContactAndProduct
     */
    public function testFetchOpportunity(Opportunity $opportunity)
    {
        $actualOpportunity = self::$client->fetchOpportunity($opportunity->id);

        $this->assertEquals($opportunity->id, $actualOpportunity->id);
        $this->assertEquals($opportunity->comment, $actualOpportunity->comment);

        $this->assertInstanceOf('Beleed\Client\Model\Product', $actualOpportunity->product);
        $this->assertEquals($opportunity->product->id, $actualOpportunity->product->id);

        $this->assertInstanceOf('Beleed\Client\Model\Contact', $actualOpportunity->contact);
        $this->assertEquals($opportunity->contact->id, $actualOpportunity->contact->id);
    }
}
