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

## Create and fetch contact

```php
<?php

use Beleed\Client\Client;
use Beleed\Client\Model\Contact;
use Buzz\Client\Curl;

$contact = new Contact;
$contact->name = 'theOrgName';
$contact->description = 'theOrgDesc';
$contact->url = 'http://theorg.url';

$client = new Client(new Curl, $accessToken);

$client->createContact($contact);

echo $contact->id;

$fetchedContact = $client->fetchContact($contact->id);
```

## Create and fetch opportunity

Using new product and contact

```php
<?php

use Beleed\Client\Client;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Contact;
use Beleed\Client\Model\Product;
use Buzz\Client\Curl;

$contact = new Contact;
$contact->name = 'theOrgName';
$contact->description = 'theOrgDesc';
$contact->url = 'http://theorg.url';

$product = new Product;
$product->name = 'theProdName';
$product->price = '99';

$opportunity = new Opportunity;
$opportunity->comment = 'aComment';
$opportunity->status = 'active';
$opportunity->confidence = '50';
$opportunity->value = '500';
$opportunity->contact = $contact;
$opportunity->product = $product;

$client = new Client(new Curl, $accessToken);

$client->createOpportunity($opportunity);

echo $opportunity->id;

$fetchedOpportunity = $client->fetchOpportunity($opportunity->id);
```

Using exists product and contact

```php
<?php

use Beleed\Client\Client;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Contact;
use Beleed\Client\Model\Product;
use Buzz\Client\Curl;

$client = new Client(new Curl, $accessToken);

$product = $client->fetchProduct('aProductId');
$contact = $client->fetchContact('aOrganizationId');

$opportunity = new Opportunity;
$opportunity->comment = 'aComment';
$opportunity->status = 'active';
$opportunity->confidence = '50';
$opportunity->value = '500';
$opportunity->contact = $contact;
$opportunity->product = $product;

$client->createOpportunity($opportunity);

echo $opportunity->id;

$fetchedOpportunity = $client->fetchOpportunity($opportunity->id);
```

Using exists product and contact ids only

```php
<?php

use Beleed\Client\Client;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Contact;
use Beleed\Client\Model\Product;
use Buzz\Client\Curl;

$client = new Client(new Curl, $accessToken);

$opportunity = new Opportunity;
$opportunity->comment = 'aComment';
$opportunity->status = 'active';
$opportunity->confidence = '50';
$opportunity->value = '500';
$opportunity->contact_id = 'aContactId';
$opportunity->product_id = 'aProductId';

$client->createOpportunity($opportunity);

echo $opportunity->id;

$fetchedOpportunity = $client->fetchOpportunity($opportunity->id);
```
