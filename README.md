# mejohLibrary

A PHP library for service functions and utilities.

## Installation

You can install `mejohLibrary` via Composer. Run the following command in your terminal:

```bash
composer require mejoh/mejoh-library:dev-main
```

## Package Reference
#### Module


|   Name    | Description                |
| :-------- | :------------------------- |
| `Client` | For request fetch client.   |
| `IpLocator` | For getting country information by locate their IP address.   |
| `Math` | For mathematic functionality usage.  |
| `Currency` | For currency conversion utilities.  |
| `Validation` | For validation functionality and utilities.  |
| `Hashing` | For hashing utilities.  |
| `Captcha` | For generating captcha.  |


## Usage/Examples

```php

use MejohLibrary\IpLocator;
use MejohLibrary\Math;
use MejohLibrary\Client;
use MejohLibrary\Currency;
use MejohLibrary\Validation;
use MejohLibrary\Hashing;
use MejohLibrary\Captcha;

require 'vendor/autoload.php';

////IPLocator CLASS////
$ipaddress = '34.124.137.169';
$ipLocator = new IpLocator($ipaddress);

////MATH CLASS////
$math = new Math();
echo 'remainder - ' . $math->remainder(23,3);

////CLIENT CLASS////
//To get API Key - please visit https://marketdata.tradermade.com//
$client = new Client($baseurl, $headers);
$response = $client->request($uriurl, 'GET', $bodyData);

////CURRENCY CLASS////
$apikey = 'API_KEY_HERE';
$currency = new Currency($apikey);

////Validation CLASS////
$validation = new Validation();
$pasw = $validation->generatePassword(30);

////HASHING CLASS////
$hashing = new Hashing();
$generate = $hashing->generate($code, $value);

////CAPTCHA CLASS////
$captcha = new Captcha();
$captcha->setText('123');
$data = $captcha->generateBase64();
```

## Authors

- [@Hamzah(Github)](https://github.com/hamzah014)