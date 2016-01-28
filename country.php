<?php
header('Content-Type: text/html; charset=UTF-8');
require 'config.php';
require 'vendor/autoload.php';
require 'tracker.php';
if (isset($_GET['item'])) {
    $track = new Tracker($_GET['item']);
    echo "<pre>", print_r(base64_decode($_GET['item'])), "</pre>";
}
use GeoIp2\Database\Reader;
echo "hi<br>";

if (is_bot($_SERVER["HTTP_USER_AGENT"])) {
    echo "Bot<br>";
} else {
    echo "real Visitor<br>";
}

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

function is_bot($user_agent) {
    $crawlersNames = 'Safari|Bloglines subscriber|Dumbot|Sosoimagespider|QihooBot|FAST-WebCrawler|Superdownloads Spiderman|LinkWalker|msnbot|ASPSeek|WebAlta Crawler|Lycos|FeedFetcher-Google|Yahoo|YoudaoBot|AdsBot-Google|Googlebot|Scooter|Gigabot|Charlotte|eStyle|AcioRobot|GeonaBot|msnbot-media|Baidu|CocoCrawler|Google|Charlotte t|Yahoo! Slurp China|Sogou web spider|YodaoBot|MSRBOT|AbachoBOT|Sogou head spider|AltaVista|IDBot|Sosospider|Yahoo! Slurp|Java VM|DotBot|LiteFinder|Yeti|Rambler|Scrubby|Baiduspider|accoona|Google|msnbot|Rambler|Yahoo|AbachoBOT|accoona|AcioRobot|ASPSeek|CocoCrawler|Dumbot|FAST-WebCrawler|GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|AltaVista|IDBot|eStyle|Scrubby';
    $preg = preg_match('/bot|spider|curl|crawl/i', $user_agent);
    //$preg = preg_match("/{$crawlersNames}/i", $user_agent);
    return !empty($user_agent) ? $preg > 0 : false;
}