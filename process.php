<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	require 'config.php';
	require 'vendor/autoload.php';
	require 'tracker.php';
	if (isset($_GET['item'])) {
	    $track = new Tracker($_GET['item']);
	    $track->hit();

	    echo json_encode(array("success" => true));
	}
} else {
	echo json_encode(array("success" => false));
}