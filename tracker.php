<?php
use GeoIp2\Database\Reader;

/**
* Tracker Class
* @author Faizan Ayubi
*/
class Tracker {

	public $item;

	function __construct($item_id) {
		$this->initialize($item_id);
		$this->googleAnalytics();
		
		$cookie = "i".$this->item->id."u".$this->item->user_id;
		if(!isset($_COOKIE[$cookie])) {
	        setcookie($cookie, $cookie);
	        $_COOKIE[$cookie] = $cookie;

	        if (isset($this->item->user_id)) {
	        	$this->mongo();
	    	}
	    }

	}

	public function toObject($array) {
        $result = new \stdClass();
        foreach ($array as $key => $value) {
        	$result->{$key} = $value;
        }
        return $result;
    }

	public function initialize($item_id) {
		$item = array();
		$str = base64_decode($item_id);
		$datas = explode("&", $str);
		foreach ($datas as $data) {
		    $property = explode("=", $data);
		    $item[$property[0]] = $property[1];
		}

		$this->item = $this->toObject($item);
	}

	function image() {
	    $img = explode(".", $this->item->image);
	    $cdn = CDN . "resize/{$img[0]}-470x246.{$img[1]}";
	    return $cdn;
	}


	public function googleAnalytics() {
		$target = parse_url($this->item->url);
		$data = array(
			"v" => 1,
			"tid" => GA,
			"cid" => $this->item->id,
			"t" => "pageview",
			"dp" => $this->item->id,
			"uid" => $this->item->user_id,
			"ua" => "CloudStuff",
			"cn" => $this->item->title,
			"cs" => $this->item->user_id,
			"cm" => ADNETWORK,
			"ck" => $this->item->username,
			"ci" => $this->item->id,
			"dl" => $this->item->url,
			"dh" => $target["host"],
			"dp" => $target["path"],
			"dt" => $this->item->title
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

	public function mongo() {
		$today = strftime("%Y-%m-%d", strtotime('now'));
		$country = $this->country();
		$m = new MongoClient();
		$db = $m->stats;

		$collection = $db->hits;
		$doc = array(
			'item_id' => $this->item->id,
			'user_id' => $this->item->user_id,
			'click' => 1,
			'country' => $country,
			'created' => $today
		);

		$record = $collection->findOne(array('item_id' => $this->item->id,'user_id' => $this->item->user_id, 'country' => $country, 'created' => $today));
		if (isset($record)) {
			$collection->update(array('item_id' => $this->item->id,'user_id' => $this->item->user_id,'country' => $country,'created' => $today), array('$set' => array("click" => $record["click"]+1)));
		} else{
			$collection->insert($doc);
		}
	}

	public function country() {
		// This creates the Reader object, which should be reused across
		// lookups.
		$reader = new Reader(maxmind_db_path);

		// Replace "city" with the appropriate method for your database, e.g.,
		// "country".
		$record = $reader->country($this->get_client_ip());

		//print($record->country->isoCode . "\n"); // 'US'
		
		return $record->country->isoCode;
	}

	function get_client_ip() {
	    $ipaddress = '';
	    if ($_SERVER['HTTP_CLIENT_IP'])
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if($_SERVER['HTTP_X_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if($_SERVER['HTTP_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if($_SERVER['HTTP_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if($_SERVER['REMOTE_ADDR'])
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

}