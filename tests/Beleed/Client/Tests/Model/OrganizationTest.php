<?php
namespace Beleed\Client\Tests\Model;

use Beleed\Client\Model\Organization;

class OrganizationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithoutAnyArguments()
    {
        new Organization;
    }
}