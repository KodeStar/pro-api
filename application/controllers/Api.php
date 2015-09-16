<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Api extends CI_Controller {

		public $ttype = '';
		public $tid = '';

		public $project = '';
		public $project_url = '';

		private $memkeytype;

		public function __construct()
		{
			date_default_timezone_set('UTC');
			parent::__construct();// Init parent contructor
			$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
			//error_reporting(E_ALL);
			//ini_set("display_errors", 1);
			

			$this->register_server();

			if( isset( $_SERVER['HTTP_CLIENT_KEY'] ) && !empty( $_SERVER['HTTP_CLIENT_KEY'] ) ) $_GET["client_key"] = $_SERVER['HTTP_CLIENT_KEY'];
		}

		private function register_server() {

			// Check if registration has been set
			$server_details = $this->cache->get( 'server_details' );
			if( empty( $server_details ) ) {
				// register server
			} else {
				$this->project = $server_details['project'];
				$this->project_url = $server_details['project_url'];
			}

		}
		


		//Public method for access api.
		//This method dynmically call the method based on the query string
		public function index()
		{
			$uri = $_SERVER['REQUEST_URI'];
	        $method = $_SERVER['REQUEST_METHOD'];
	        $paths = explode('/', $this->paths($uri));
	        $resource = array_slice($paths, 1); // remove empty value and version number
			$endpoint = $resource[0];
			$options = array_slice($resource, 1);

			if( empty( $endpoint ) ) {

				$this->show_stats();

			} else {

				if((int)method_exists($this,$endpoint) > 0) $this->$endpoint($options);
				else $this->rest->response('',404 ); 
				// If the method not exist with in this class, response would be "Page not found".

			}

		}

		private function show_stats() {
			$this->load->view('stats');
		}
		
		private function movies($subpoints)
		{
			if(is_array($subpoints) && !empty($subpoints)) 
			{
				switch($subpoints[0]) {
					case "latest":
						switch($this->get_request_method()) {
							case "GET": $this->getLatestMovies(); break;
							default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid Method")),405); break;
						}
						break;
					default: // check if its the movie id
						switch($this->get_request_method()) {
							case "GET": $this->getMovieById($subpoints[0]); break;
							default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
						}
						
						break;
				}
			} else {
				switch($this->get_request_method()) {
					case "GET": $this->rest->response($this->json(array("status" => "ok", "total movies" => "0")),200); break;
					default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
				}
				
			}
		}

		private function tv($subpoints)
		{
			if(is_array($subpoints) && !empty($subpoints)) 
			{
				switch($subpoints[0]) {
					case "latest":
						switch($this->get_request_method()) {
							case "GET": $this->getLatestSeries(); break;
							default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
						}
						break;
					default: // check if its the movie id
						switch($this->get_request_method()) {
							case "GET": $this->getSeriesById($subpoints[0]); break;
							default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
						}
						
						break;
				}
			} else {
				switch($this->get_request_method()) {
					case "GET": $this->rest->response($this->json(array("status" => "ok", "total movies" => "0")),200); break;
					default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
				}
				
			}
		}

		private function music($subpoints)
		{
			if(is_array($subpoints) && !empty($subpoints)) 
			{
				switch($subpoints[0]) {
					case "latest":
						switch($this->get_request_method()) {
							case "GET": $this->getLatestArtists(); break;
							default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
						}
						break;
					case "albums":
						switch($this->get_request_method()) {
							case "GET": $this->getArtistById($subpoints[1], true); break;
							default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
						}
						break;
					case "labels":
						switch($this->get_request_method()) {
							case "GET": $this->getLabelById($subpoints[1], true); break;
							default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
						}
						break;
					default: // check if its the movie id
						switch($this->get_request_method()) {
							case "GET": $this->getArtistById($subpoints[0]); break;
							default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
						}
						
						break;
				}
			} else {
				switch($this->get_request_method()) {
					case "GET": $this->rest->response($this->json(array("status" => "ok", "total movies" => "0")),200); break;
					default: $this->rest->response($this->json(array("status" => "error", "error message" => "Invalid method")),405); break;
				}
				
			}
		}

		public function getType($type_var) {
			if(($type = $this->get_row("SELECT type_id FROM fanart_types WHERE type_var = '".mysql_real_escape_string($type_var)."'")) !== NULL) {
				return $type["type_id"];
			} else return 1;
		}

		



		private function getMovieById($movieid) {
			
			$id = preg_replace("/[^a-z0-9-]/", "", $movieid);
			$this->ttype = 'movie';
			$this->tid = $id;

		}
			
		private function getSeriesById($movieid) {
			
			$id = preg_replace("/[^a-z0-9-]/", "", $movieid);
			$this->ttype = 'tv';
			$this->tid = $id;
		}
		
		private function getLabelById($movieid) {
			
			$id = preg_replace("/[^a-z0-9-]/", "", $movieid);
			$this->ttype = 'music';
			$this->tid = $id;

		}
		
		
		private function getArtistById($movieid, $albums=false) {
			
			$id = preg_replace("/[^a-z0-9-]/", "", $movieid);
			$this->ttype = 'music';
			$this->tid = $id;

		}
		
		
		
	    private function paths($url) {
	        $uri = parse_url($url);
	        return $uri['path'];
	    }
		
		
		//Encode array into JSON
		private function json($data)
		{
			if(is_array($data)){
				return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			}
		}
	}
