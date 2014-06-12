<?php
namespace Beleed\Client\Tests\Model;

use Beleed\Client\Model\Opportunity;

class OpportunityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithoutAnyArguments()
    {
        new Opportunity;
    }
}