<?php
namespace Beleed\Client\Model;

class Opportunity
{
    public $id;
    public $value;
    public $confidence;
    public $comment;
    public $status;
    public $closed_at;
    public $organization_id;
    public $product_id;

    /**
     * @var Organization|null
     */
    public $organization;

    /**
     * @var Product|null
     */
    public $product;
}