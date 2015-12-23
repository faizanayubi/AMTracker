<?php

class CouchDB {
	function __construct($host = "localhost", $port = 5984) {
		$this->host = $host;
		$this->port = $port;
    }

    public function send($method, $url, $post_data = NULL) {
    	$s = fsockopen($this->host, $this->port, $errno, $errstr);
    	if(!$s) {
    		echo "$errno: $errstr\n"; 
    		return false;
    	}

    	$request = "$method $url HTTP/1.0\r\nHost: $this->host\r\n";

    	if ($this->user) {
    		$request .= "Authorization: Basic ".base64_encode("$this->user:$this->pass")."\r\n";
    	}

    	if($post_data) {
    		$request .= "Content-Length: ".strlen($post_data)."\r\n\r\n";
    		$request .= "$post_data\r\n";
    	} else {
    		$request .= "\r\n";
    	}

    	fwrite($s, $request);
    	$response = ""; 

    	while(!feof($s)) {
    		$response .= fgets($s);
    	}

    	list($this->headers, $this->body) = explode("\r\n\r\n", $response); 
    	return $this->body;
    }

    public function hit($data) {
    	$resp = $this->send("POST", "/stats", json_encode($data));
    }
}