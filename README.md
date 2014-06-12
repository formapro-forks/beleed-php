# Beleed PHP client

## Create and fetch product

```php
<?php

use Beleed\Client\Client;
use Beleed\Client\Model\Product;
use Buzz\Client\Curl;

$product = new Product;
$product->name = 'theProdName';
$product->price = '99';

$client = new Client(new Curl, $accessToken);

$client->createProduct($product);

echo $product->id;

$fetchedProduct = $client->fetchProduct($product->id);
```

## Create and fetch organization

```php
<?php

use Beleed\Client\Client;
use Beleed\Client\Model\Organization;
use Buzz\Client\Curl;

$organization = new Organization;
$organization->name = 'theOrgName';
$organization->description = 'theOrgDesc';
$organization->url = 'http://theorg.url';

$client = new Client(new Curl, $accessToken);

$client->createOrganization($organization);

echo $organization->id;

$fetchedOrganization = $client->fetchOrganization($organization->id);
```

## Create and fetch opportunity

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

$fetchedOpportunity = $client->fetchOpportunity($opportunity->id);
```