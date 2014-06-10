# Beleed PHP client

## Create opportunity

```php
<?php

use Beleed\Client\Client;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Organization;
use Beleed\Client\Model\Product;
use Buzz\Client\Curl;

$organization = new Organization;
$organization->name = 'theOrgName';
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

$client = new Client(new Curl, $accessToken);

$client->createOpportunity($opportunity);

echo $opportunity->id;

$client->fetchOpportunity($opportunity->id);
```