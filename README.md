# mejohLibrary

A PHP library for service functions and utilities.
Reference URL : [mejohLibrary](https://github.com/hamzah014/mejohLibrary.git)

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
| `QRCode` | For generating QR Code.  |


## Usage/Examples

```php

use MejohLibrary\IpLocator;
use MejohLibrary\Math;
use MejohLibrary\Client;
use MejohLibrary\Currency;
use MejohLibrary\Validation;
use MejohLibrary\Hashing;
use MejohLibrary\Captcha;
use MejohLibrary\QRCode;

require 'vendor/autoload.php';

////IPLocator CLASS////
$ipaddress = '34.124.137.169';
$ipLocator = new IpLocator($ipaddress);

////MATH CLASS////
$math = new Math();
echo 'remainder - ' . $math->remainder(23,3);

////CLIENT CLASS////
$baseurl = 'https://www.xe.com/currencytables/';
$request_type = 'GET';
$header = [];
$body = [];
$client = new Client();
$request = $client->config($baseurl, $header)
            ->method($request_type)
            ->body($body)
            ->request();

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
$captcha = Captcha::create()
        ->setHeight(100)
        ->setWidth(100)
        ->setText('Your Text')
        ->setFontSize(20)
        ->generateBase64();

////CAPTCHA CLASS////
$qrcode = new QRCode();
$uri = $qrcode->generate()
->setData('Test')
->setBackgroundColor('#121112')
->setForegroundColor('#ed0eb9')
->buildUri();
```

## Authors

- [@Hamzah(Github)](https://github.com/hamzah014)