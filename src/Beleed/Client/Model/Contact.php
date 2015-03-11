<?php
namespace Beleed\Client\Model;

class Contact
{
    public $id;
    public $name;
    public $email;
    public $email_wildcard_domain;
    public $organization_name;
    public $phone;
    public $url;
    public $address;
    public $gender;
    public $city;
    public $position;
    public $birthday;
    public $tags;
    public $description;
    public $source;
    public $shared;
    public $sessions;

    public function __construct()
    {
        $this->source = "website";
        $this->shared = true;
        $this->sessions = 1;
    }
}
