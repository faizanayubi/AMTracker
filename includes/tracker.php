<?php
use GeoIp2\Database\Reader;

/**
* Tracker Class
* @author Faizan Ayubi
*/
class LinkTracker {

	public $link;

	function __construct($link_id) {
		if (!$this->is_bot($_SERVER["HTTP_USER_AGENT"])) {
			$link_id = base64_decode($link_id);
			$this->initialize($link_id);
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
		$cookie = "track".$this->link->link_id;
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
	    $preg = preg_match('/bot|spider|curl|crawl/i', $user_agent);
	    return !empty($user_agent) ? $preg > 0 : false;
    }

	public function toObject($array) {
        $result = new \stdClass();
        foreach ($array as $key => $value) {
        	$result->{$key} = $value;
        }
        return $result;
    }

	public function image() {
	    $img = explode(".", $this->link->image);
	    $cdn = CDN . "resize/{$img[0]}-". DIMENSION .".{$img[1]}";
	    return $cdn;
	}

	public function redirectUrl() {
		$track = "?utm_source=". $this->link->user_id ."&utm_medium=EarnBugs&utm_campaign=".$this->link->title."&utm_term=".$this->link->user_id."&utm_content=".$this->link->title;
		$string = str_replace("'", '-', $this->removeEmoji($this->link->url).$track);
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

	public function initialize($link_id) {
		$m = new MongoClient();
		$db = $m->stats;
		$urls = $db->urls;
		$link = $urls->findOne(array('link_id' => (int) $link_id));
		if (isset($link)) {
			$this->link = $this->toObject($link);
		}
	}

	public function process() {
		$c = $this->cookie();
		if ($c < 4) {
			$this->mongo();
		}
		
	}

	public function mongo() {
		$today = strftime("%Y-%m-%d", strtotime('now'));
		$country = $this->country();
		
		$m = new MongoClient();
		$db = $m->stats;
		$clicks = $db->clicks;
		$doc = array(
			'link_id' => $this->link->link_id,
			'item_id' => $this->link->item_id,
			'user_id' => $this->link->user_id,
			'click' => 1,
			'country' => $country,
			'created' => $today
		);

		$record = $clicks->findOne(array('link_id' => $this->link->link_id, 'country' => $country, 'created' => $today));
		if (isset($record)) {
			$clicks->update(array('link_id' => $this->link->link_id,'item_id' => $this->link->item_id,'user_id' => $this->link->user_id,'country' => $country,'created' => $today), array('$set' => array("click" => $record["click"]+1)));
		} else{
			$clicks->insert($doc);
		}
	}

	public function country() {
		$reader = new Reader(maxmind_db_path);
		$record = $reader->country($this->get_client_ip());
		return !empty($record->country->isoCode)? $record->country->isoCode : "IN";
	}

	function get_client_ip() {
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    $ip = explode(",", $ipaddress);
    	return $ip[0];
	}

}