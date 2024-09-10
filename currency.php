<?php
use MejohLibrary\Currency;

require 'vendor/autoload.php';

$apikey = 'UAHflQitdZigQKsGR62M';
$currency = new Currency($apikey);

$data = $currency->convert('USD', 'MYR', 360);

print_r($data);

$arr = [
        [
            'from'=> "MYR",
            'to'=> "IDR",
        ],
        [
            'from'=> "MYR",
            'to'=> "USD",
        ]
    ];

$data2 = $currency->conversion($arr);

print_r($data2);