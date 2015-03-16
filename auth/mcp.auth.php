<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_mcp{

	public function index(){
		$data["users"] = ee()->db->select("*")->get("auth")->result();
		return ee()->load->view('index',$data,TRUE);
	}
}