<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Memcache extends CI_Driver_Library {

		public $valid_drivers = array( 'redis', 'memcache' );
		public $active = 'memcache';
		public $CI;
    
    	function __construct() {
        	$this->CI =& get_instance();
    	}

		public function get($key){
			return $this->$this->active->get( $key );
		}

		public function set($key,$value,$timeout=86400){
			return $this->$this->active->set( $key,$value,$timeout );
		}

	}