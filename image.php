<?php

require 'config.php';

if (isset($_GET['name'])) {
	$cdn = CDN . "resize/" .$_GET['name'];
	header("Location: {$cdn}");
	exit();
} else {
	header("Location: /logo.png");
    exit();
}