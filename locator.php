<?php
use MejohLibrary\IpLocator;

require 'vendor/autoload.php';

$ipaddress = '34.124.137.169';
$ipLocator = new IpLocator($ipaddress);

print_r($ipLocator->getAll());