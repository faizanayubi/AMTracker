<?php
global $track;
if (isset($_GET['id'])) {
    require 'includes/config.php';
    require 'includes/vendor/autoload.php';
    require 'includes/tracker.php';

    $track = new LinkTracker($_GET['id']);
    //echo "<pre>", print_r($track->link), "</pre>";
    include 'view/dynamic.php';
} else {
    include 'view/static.php';
}