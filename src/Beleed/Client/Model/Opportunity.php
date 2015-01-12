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
    public $source;

    /**
     * @var Contact|null
     */
    public $contact;

    /**
     * @var Product|null
     */
    public $product;
}
