<?php

require 'config.php';
require 'vendor/autoload.php';
require 'tracker.php';
if (isset($_GET['item'])) {
    $track = new Tracker($_GET['item']);
}
