<?php
date_default_timezone_set('Asia/Kolkata');
global $track;
if (isset($_GET['id'])) {
    require 'includes/config.php';
    require 'includes/vendor/autoload.php';
    require 'includes/tracker.php';

    $track = new LinkTracker($_GET['id']);
    if (isset($track->link)) {
        //$track->process();
    	include 'view/dynamic.php';
    } else {
    	include 'view/static.php';
    }
} else {
    include 'view/static.php';
}