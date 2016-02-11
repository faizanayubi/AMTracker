<?php
require 'includes/config.php';
require 'includes/vendor/autoload.php';
require 'includes/tracker.php';
use GeoIp2\Database\Reader;
echo "Country Code : ".country();

echo "<br>IP: ".get_client_ip();
function country() {

	// This creates the Reader object, which should be reused across
	// lookups.
	$reader = new Reader(maxmind_db_path);

	// Replace "city" with the appropriate method for your database, e.g.,
	// "country".
	$record = $reader->country(get_client_ip());

	return !empty($record->country->isoCode)? $record->country->isoCode : "IN";
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
    $ip = explode(",", $ipaddress);
    return $ip[0];
}