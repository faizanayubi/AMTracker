<?php
use GeoIp2\Database\Reader;
require 'vendor/autoload.php';

country();

function country() {

	// This creates the Reader object, which should be reused across
	// lookups.
	$reader = new Reader('/var/www/addon/GeoLite2-Country.mmdb');

	// Replace "city" with the appropriate method for your database, e.g.,
	// "country".
	$record = $reader->country(get_client_ip());

	print($record->country->isoCode . "\n"); // 'US'
}

function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}