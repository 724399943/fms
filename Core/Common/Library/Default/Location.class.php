<?php
use Think\Controller;

class Location extends Controller {
	private $postobj;

	public function __construct($data) {
		parent::__construct();
		$this->postobj = $data;
	}

	public function index() {
		// $data = array(
		// 	'latitude'  => "{$this->postObj['Latitude']}",
		// 	'longitude' => "{$this->postObj['Longitude']}"
		// );
		// M('agent')->where(array('open_id'=>"{$this->postObj['FromUserName']}"))->data($data)->save();
	}
}