# mejohLibrary

A PHP library for service functions and utilities.

## Installation

You can install `mejohLibrary` via Composer. Run the following command in your terminal:

```bash
composer require mejoh/mejoh-library:dev-main
```

## Pacakge Reference
#### Module


|   Name    | Description                |
| :-------- | :------------------------- |
| `Client ` | For request fetch client.   |
| `IpLocator ` | For getting country information by locate their IP address.   |
| `Math ` | For mathematic functionality usage.  |


## Usage/Examples

```php

use MejohLibrary\IpLocator;
use MejohLibrary\Math;
use MejohLibrary\Client;

require 'vendor/autoload.php';

////IPLocator CLASS////
$ipaddress = '34.124.137.169';
$ipLocator = new IpLocator($ipaddress);

////MATH CLASS////
$math = new Math();
echo 'remainder - ' . $math->remainder(23,3);

////CLIENT CLASS////
$client = new Client($baseurl, $headers);
$response = $client->request($uriurl, 'GET', $bodyData);
```

## Authors

- [@Hamzah(Github)](https://github.com/hamzah014)