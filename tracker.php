<?php

/**
* Tracker Class
* @author Faizan Ayubi
*/
class Tracker {

	public $item;

	function __construct($item) {
		$this->item = $item;

		if(!isset($_COOKIE['track'])) {
	        setcookie('track', $item["id"]);
	        $_COOKIE['track'] = $item["id"];

	        $this->googleAnalytics($item);

	        $this->send($item["id"]);
	    }

	}

	function googleAnalytics($item) {
		$target = parse_url($item["url"]);
		$data = array(
			"v" => 1,
			"tid" => "UA-69054592-2",
			"cid" => $item["id"],
			"t" => "pageview",
			"dp" => $item["id"],
			"uid" => $item["username"],
			"ua" => "CloudStuff",
			"cn" => $item["title"],
			"cs" => $item["username"],
			"cm" => "earnbugs",
			"ck" => $item["username"],
			"ci" => $item["id"],
			"dl" => $item["url"],
			"dh" => $target["host"],
			"dp" => $target["path"],
			"dt" => $item["title"]
		);

	    $url = "https://www.google-analytics.com/collect?".http_build_query($data);
	    // Get cURL resource
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_URL => $url,
	        CURLOPT_USERAGENT => 'CloudStuff'
	    ));

	    $resp = curl_exec($curl);
	    curl_close($curl);
	}

	function send($id) {
	    $url = 'https://api-eu.clusterpoint.com/v4/2538/FraudLinks';
	    $userPassword = 'info@earnbugs.in:Swift123';

	    $doc = array(
	        'link' => $id,
	        'timestamp' => time()
	    );

	    $ch = curl_init();
	    // https://api-eu.clusterpoint.com/v4/ACCOUNT_ID/DATABASE_NAME[ID]     -  Insert single document with explicit ID
	    curl_setopt($ch, CURLOPT_URL, $url); //  In this case document ID is automatically generated by system.
	    curl_setopt($ch, CURLOPT_USERPWD, $userPassword);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($doc));
	    $response = curl_exec($ch);
	    $errorMsg = curl_error($ch);
	    if ($errorMsg) {
	        var_dump($errorMsg);
	    }
	    curl_close($ch);
	}

}