<?php
namespace Beleed\Client\Tests\Model;

use Beleed\Client\Model\Contact;

class ContactTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithoutAnyArguments()
    {
        new Contact;
    }
}
