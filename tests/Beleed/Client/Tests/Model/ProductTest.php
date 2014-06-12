<?php
namespace Beleed\Client\Tests\Model;

use Beleed\Client\Model\Product;

class ProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithoutAnyArguments()
    {
        new Product;
    }
}