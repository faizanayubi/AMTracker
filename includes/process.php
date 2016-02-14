<?php
	require 'config.php';
    require 'vendor/autoload.php';
    require 'tracker.php';

    $arr["success"] = false;
	
	if (isset($_GET['id'])) {
		$track = new LinkTracker($_GET['id']);
		if (isset($track)) {
			if ($track->is_ajax()) {
				$track->process();
				$arr["success"] = true;
			}
		}
	}

	echo json_encode($arr);