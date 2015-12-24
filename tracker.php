<?php

/**
* Tracker Class
* @author Faizan Ayubi
*/
class Tracker extends ClusterPoint {

	public $item;

	function __construct($item) {
		$this->item = $item;

		if(isset($item["id"])) {
			$this->googleAnalytics($item);
			
			$cookie = "i".$item["id"]."u".$item["user_id"];
			if(!isset($_COOKIE[$cookie])) {
		        setcookie($cookie, $item["id"]);
		        $_COOKIE[$cookie] = $item["id"];

		        if (isset($item["user_id"])) {

		        	$this->mongo($item);
		        	$record = $this->read($item);
		        	if($record) {
		        		$data = $record[0];
		        		$this->update($data->_id, $data->click + 1);
		        	} else {
		        		$this->create($item);
		        	}
		    	}
		    }
		}

	}

	public function googleAnalytics($item) {
		$target = parse_url($item["url"]);
		$data = array(
			"v" => 1,
			"tid" => "UA-70464246-1",
			"cid" => $item["id"],
			"t" => "pageview",
			"dp" => $item["id"],
			"uid" => $item["user_id"],
			"ua" => "CloudStuff",
			"cn" => $item["title"],
			"cs" => $item["user_id"],
			"cm" => "chocghar",
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

	public function mongo($item) {
		$today = strftime("%Y-%m-%d", strtotime('now'));
		$m = new MongoClient();
		$db = $m->stats;

		$collection = $db->hits;
		$doc = array(
			'item_id' => $item["id"],
			'user_id' => $item["user_id"],
			'click' => 1,
			'created' => $today
		);

		$record = $collection->findOne(array('item_id' => $item["id"],'user_id' => $item["user_id"],'created' => $today));
		if (isset($record)) {
			$collection->update(array('item_id' => $item["id"],'user_id' => $item["user_id"],'created' => $today), array('$set' => array("click" => $record["click"]+1)));
		} else{
			$collection->insert($doc);
		}
	}

}