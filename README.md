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

Using new product and organization

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

Using exists product and organization

```php
<?php

use Beleed\Client\Client;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Organization;
use Beleed\Client\Model\Product;
use Buzz\Client\Curl;

$client = new Client(new Curl, $accessToken);

$product = $client->fetchProduct('aProductId');
$organization = $client->fetchOrganization('aOrganizationId');

$opportunity = new Opportunity;
$opportunity->comment = 'aComment';
$opportunity->status = 'active';
$opportunity->confidence = '50';
$opportunity->value = '500';
$opportunity->organization = $organization;
$opportunity->product = $product;

$client->createOpportunity($opportunity);

echo $opportunity->id;

$fetchedOpportunity = $client->fetchOpportunity($opportunity->id);
```

Using exists product and organization ids only

```php
<?php

use Beleed\Client\Client;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Organization;
use Beleed\Client\Model\Product;
use Buzz\Client\Curl;

$client = new Client(new Curl, $accessToken);

$opportunity = new Opportunity;
$opportunity->comment = 'aComment';
$opportunity->status = 'active';
$opportunity->confidence = '50';
$opportunity->value = '500';
$opportunity->organization_id = 'aOrganizationId';
$opportunity->product_id = 'aProductId';

$client->createOpportunity($opportunity);

echo $opportunity->id;

$fetchedOpportunity = $client->fetchOpportunity($opportunity->id);
```