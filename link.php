<?php
/**
* Link Class
* @author Faizan Ayubi
*/
class Link {

	public $item;

	function __construct($item_id) {
		$this->initialize($item_id);
		$this->googleAnalytics();
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


	public function googleAnalytics() {
		$target = parse_url($this->item->url);
		$data = array(
			"v" => 1,
			"tid" => GA,
			"cid" => $this->item->id,
			"t" => "pageview",
			"dp" => $this->item->id,
			"uid" => $this->item->user_id,
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
	        CURLOPT_URL => $url
	    ));

	    $resp = curl_exec($curl);
	    curl_close($curl);
	}
}