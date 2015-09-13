<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Redis extends CI_Driver {

		public function get($key){
			$memcache = new Memcache;
			$memcache->connect('localhost', 11211);
			//$memcache->flush();
			$result = $memcache->get($key);
			return $result;
		}

		public function set($key,$value,$timeout=86400){
			$memcache = new Memcache;
			$memcache->connect('localhost', 11211);
			//$memcache->flush();
			$result = $memcache->get($key);
			if(empty($result)){  //store in memcache
				$memcache->set($key,$value,MEMCACHE_COMPRESSED,$timeout);
			} else {
				$memcache->replace($key,$value,MEMCACHE_COMPRESSED,$timeout);
			}
			return $result;
		}

	}