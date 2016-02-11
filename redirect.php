<?php
    require 'includes/config.php';
    require 'includes/vendor/autoload.php';
    require 'includes/tracker.php';

    if (isset($_GET['id'])) {
        include 'view/dynamic.php';
    } else {
        include 'view/static.php';
    }