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
	}

	public function hit() {
		$this->googleAnalytics();

		if (!$this->is_bot($_SERVER["HTTP_USER_AGENT"])) {
			$c = $this->cookie();
			if ($c == 1) {
				$this->mongo();
			}
		}
	}


	public function is_ajax() {
		$ajax = false;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$ajax = true;
		}

		return $ajax;
	}

	/**
	 * checks if cookie exists then return true else returns false and creates cookie
	 * @return bool
	 */
	public function cookie() {
		$cookie = "i".$this->item->id."u".$this->item->user_id;
		$value = 1;
		if(!isset($_COOKIE[$cookie])) {
	        setcookie($cookie, $value);
	        $_COOKIE[$cookie] = $value;
	    } else {
	    	$value = $_COOKIE[$cookie];
	    	setcookie($cookie, ++$value);
	    }
	    return $value;
	}

	public function is_bot($user_agent) {
		$crawlersNames = 'Safari|Bloglines subscriber|Dumbot|Sosoimagespider|QihooBot|FAST-WebCrawler|Superdownloads Spiderman|LinkWalker|msnbot|ASPSeek|WebAlta Crawler|Lycos|FeedFetcher-Google|Yahoo|YoudaoBot|AdsBot-Google|Googlebot|Scooter|Gigabot|Charlotte|eStyle|AcioRobot|GeonaBot|msnbot-media|Baidu|CocoCrawler|Google|Charlotte t|Yahoo! Slurp China|Sogou web spider|YodaoBot|MSRBOT|AbachoBOT|Sogou head spider|AltaVista|IDBot|Sosospider|Yahoo! Slurp|Java VM|DotBot|LiteFinder|Yeti|Rambler|Scrubby|Baiduspider|accoona|Google|msnbot|Rambler|Yahoo|AbachoBOT|accoona|AcioRobot|ASPSeek|CocoCrawler|Dumbot|FAST-WebCrawler|GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|AltaVista|IDBot|eStyle|Scrubby';
	    $preg = preg_match('/bot|spider|curl|crawl/i', $user_agent);
	    //$preg = preg_match("/{$crawlersNames}/i", $user_agent);
	    return !empty($user_agent) ? $preg > 0 : false;
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
		    $item[$property[0]] = trim(preg_replace('/\s+/', ' ', $property[1]));
		}

		$this->item = $this->toObject($item);
	}

	public function image() {
	    $img = explode(".", $this->item->image);
	    $cdn = CDN . "resize/{$img[0]}-". DIMENSION .".{$img[1]}";
	    return $cdn;
	}

	public function image_name() {
	    $img = explode(".", $this->item->image);
	    $cdn = "{$img[0]}-". DIMENSION .".{$img[1]}";
	    return $cdn;
	}

	public function redirectUrl() {
		$track = "?utm_source=".$this->item->user_id."&utm_medium=".ADNETWORK."&utm_campaign=".$this->item->title;
		$string = str_replace("'", '-', $this->removeEmoji($this->item->url).$track);
		return $string;
	}

	public function removeEmoji($text) {
	    $clean_text = "";

	    // Match Emoticons
	    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
	    $clean_text = preg_replace($regexEmoticons, '', $text);

	    // Match Miscellaneous Symbols and Pictographs
	    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
	    $clean_text = preg_replace($regexSymbols, '', $clean_text);

	    // Match Transport And Map Symbols
	    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
	    $clean_text = preg_replace($regexTransport, '', $clean_text);

	    // Match Miscellaneous Symbols
	    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
	    $clean_text = preg_replace($regexMisc, '', $clean_text);

	    // Match Dingbats
	    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
	    $clean_text = preg_replace($regexDingbats, '', $clean_text);

	    return $clean_text;
	}

	public function mongo() {
		$today = strftime("%Y-%m-%d", strtotime('now'));
		$country = $this->country();
		$m = new MongoClient();
		$db = $m->stats;

		$collection = $db->clicks;
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
		
		return !empty($record->country->isoCode)? $record->country->isoCode : "IN";
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
	    $ip = explode(",", $ipaddress);
    	return $ip[0];
	}

}