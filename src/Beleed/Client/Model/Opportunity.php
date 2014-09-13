<?php
namespace Beleed\Client\Model;

class Opportunity
{
    public $id;
    public $contact_id;
    public $product_id;
    public $confidence;
    public $status;
    public $comment;
    public $organization_name;
    public $value;

    /**
     * @var Organization|null
     */
    public $organization;

    /**
     * @var Product|null
     */
    public $product;
}
