<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Rest {
		private $_code = 200;
		
		public function __construct()
		{
			//$this->inputs();
		}		
		
		public function response($data,$status,$format="json")
		{
			$this->_code = ($status)?$status:200;
			$this->set_headers($format);
			//$this->track();
			echo $data;
			$this->track();
			

			exit;
		}

		public function track() {
			$url = 'www.google-analytics.com';
			$page = '/collect';

			$googleip = $this->memcacheget('googleip');
			if(empty($googleip)) {
				$googleip = gethostbyname($url);
				$this->memcacheset('googleip', $googleip, 3600);
			}

			//set POST variables
			if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
				$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
			}	
			
			$fields = array(
				'v' => '1',
				'tid' => $this->GA_ID,
				'cid' => $this->gaParseCookie(),
				't' => 'pageview',
				'cm' => $_GET["api_key"],
				'dr' => $this->project_url,
				'cs' => $this->project,
				'dh' => 'webservice.fanart.tv',
				'dp' => $this->ttype.'/'.$this->tid,
				'uip' => $_SERVER['REMOTE_ADDR'],
				'ua' => $_SERVER['HTTP_USER_AGENT']
			);


			$fields_string = http_build_query($fields);
			//die($this->myfunc_getIP($url));
			$fp=fsockopen($googleip, 80, $errno, $errstr, 5);

			//$fp = fsockopen($url, 80, $errno, $errstr, 5);

			stream_set_blocking($fp, 0);
			stream_set_timeout($fp, 5);

			$output = "POST http://".$url.$page." HTTP/1.1\r\n";
			$output .= "Host: $url\r\n";
			$output .= "Content-Length: ".strlen($fields_string)."\r\n";
			$output .= "Connection: close\r\n\r\n";

			$output .= $fields_string;
			//die($output);
			fastcgi_finish_request();
			$sentData = 0;
            $toBeSentData = strlen($output);
            while($sentData < $toBeSentData) {
                $sentData += fwrite($fp, $output);
            }
			
			fclose($fp);

		}


		// Handle the parsing of the _ga cookie or setting it to a unique identifier
		private function gaParseCookie() {
		  if (isset($_COOKIE['_ga'])) {
		    list($version,$domainDepth, $cid1, $cid2) = split('[\.]', $_COOKIE["_ga"],4);
		    $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1.'.'.$cid2);
		    $cid = $contents['cid'];
		  }
		  else $cid = $this->gaGenUUID();
		  return $cid;
		}

		// Generate UUID v4 function - needed to generate a CID when one isn't available
		private function gaGenUUID() {
		  return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		    // 32 bits for "time_low"
		    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

		    // 16 bits for "time_mid"
		    mt_rand( 0, 0xffff ),

		    // 16 bits for "time_hi_and_version",
		    // four most significant bits holds version number 4
		    mt_rand( 0, 0x0fff ) | 0x4000,

		    // 16 bits, 8 bits for "clk_seq_hi_res",
		    // 8 bits for "clk_seq_low",
		    // two most significant bits holds zero and one for variant DCE1.1
		    mt_rand( 0, 0x3fff ) | 0x8000,

		    // 48 bits for "node"
		    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		  );
		}


		private function get_content_type($type)
		{
			switch($type) {
				case "xml": return "application/xml"; break;
				case "php": return "text/plain"; break;
				case "json": default: return "application/json"; break;
			}
		}
		
		public function get_request_method(){
			return $_SERVER['REQUEST_METHOD'];
		}	
			
		private function set_headers($format)
		{
			header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
			header("Content-Type:".$this->get_content_type($format)."; charset=utf-8");
		}
		
		private function get_status_message()
		{
			$status = array(
				100 => 'Continue',  
				101 => 'Switching Protocols',  
				200 => 'OK',
				201 => 'Created',  
				202 => 'Accepted',  
				203 => 'Non-Authoritative Information',  
				204 => 'No Content',  
				205 => 'Reset Content',  
				206 => 'Partial Content',  
				300 => 'Multiple Choices',  
				301 => 'Moved Permanently',  
				302 => 'Found',  
				303 => 'See Other',  
				304 => 'Not Modified',  
				305 => 'Use Proxy',  
				306 => '(Unused)',  
				307 => 'Temporary Redirect',  
				400 => 'Bad Request',  
				401 => 'Unauthorized',  
				402 => 'Payment Required',  
				403 => 'Forbidden',  
				404 => 'Not Found',  
				405 => 'Method Not Allowed',  
				406 => 'Not Acceptable',  
				407 => 'Proxy Authentication Required',  
				408 => 'Request Timeout',  
				409 => 'Conflict',  
				410 => 'Gone',  
				411 => 'Length Required',  
				412 => 'Precondition Failed',  
				413 => 'Request Entity Too Large',  
				414 => 'Request-URI Too Long',  
				415 => 'Unsupported Media Type',  
				416 => 'Requested Range Not Satisfiable',  
				417 => 'Expectation Failed',  
				500 => 'Internal Server Error',  
				501 => 'Not Implemented',  
				502 => 'Bad Gateway',  
				503 => 'Service Unavailable',  
				504 => 'Gateway Timeout',  
				505 => 'HTTP Version Not Supported');
			return ($status[$this->_code])?$status[$this->_code]:$status[500];
		}		
		
	}