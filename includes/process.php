<?php
	require 'config.php';
    require 'vendor/autoload.php';
    require 'tracker.php';
	
	if (isset($_GET['id'])) {
		$track = new LinkTracker($_GET['id']);
		$track->process();
		$arr["success"] = true;
	} else {
		$arr["success"] = false;
	}

	echo json_encode($arr);