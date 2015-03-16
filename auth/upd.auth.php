<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_upd {

	public $version = "1.1";
	public $module_name = "Auth";
	public $table_name = "auth";
	public $method_name = array("act_login","act_register","act_editProfileSave");

	public function __construct(){
		session_start();
		ee()->load->dbforge();
	}

	public function install(){
		include("template/index.tpl");
		include("template/register.tpl");
		include("template/account.tpl");
		include("template/logout.tpl");
		include("template/edit.tpl");

		$data = array(
			"snippet_name" => "global_auth_css",
			"snippet_contents" => ' <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
									<link href="{site_url}system/expressionengine/third_party/auth/css/style.css" type="text/css" rel="stylesheet" />'
		);

		ee()->db->insert("snippets",$data);

		$data = array(
			"module_name" => $this->module_name,
			"module_version" => $this->version,
			"has_cp_backend" => "y"
		);

		ee()->db->insert("modules",$data);

		$data = array(
			"group_name" => $this->table_name
		);

		ee()->db->insert("template_groups",$data);

		$group_id = ee()->db->insert_id();

		$data = array(
			array(
				"group_id" => $group_id,
				"template_name" => "index",
				"template_data" =>  $index,
				"allow_php" => "y"
			),
			array(
				"group_id" => $group_id,
				"template_name" => "register",
				"template_data" =>  $register,
				"allow_php" => "y"
			),
			array(
				"group_id" => $group_id,
				"template_name" => "account",
				"template_data" =>  $account,
				"allow_php" => "y"
			),
			array(
				"group_id" => $group_id,
				"template_name" => "logout",
				"template_data" =>  $logout,
				"allow_php" => "y"
			),
			array(
				"group_id" => $group_id,
				"template_name" => "edit",
				"template_data" =>  $edit,
				"allow_php" => "y"
			)
		);

		ee()->db->insert_batch("templates",$data);

		$template_id = ee()->db->select('template_id')
				  ->from('templates')
				  ->where("template_name","edit")
				  ->get()->result_array();
		$template_id = $template_id[0]["template_id"];

		$data = array(
			"template_id" => $template_id,
			"route" => "auth/account/edit",
			"route_parsed" => "^auth\/?account\/?edit\/?\/?$",
		);

		ee()->db->insert("template_routes",$data);

		foreach ($this->method_name as $value) {
			$data = array(
				"class" => $this->module_name,
				"method" => $value,
				"csrf_exempt" => 1
			);

			ee()->db->insert("actions",$data);
		}

		$fields = array(
			"id" => array(
				"type" => "int",
				"constraint" => "10",
				"unsigned" => TRUE,
				"auto_increment" => TRUE
			),
			"last_name" => array(
				"type" => "varchar",
				"constraint" => "255"
			),
			"first_name" => array(
				"type" => "varchar",
				"constraint" => "255"
			),
			"birthday" => array(
				"type" => "text"
			),
			"login" => array(
				"type" => "varchar",
				"constraint" => "255"
			),
			"password" => array(
				"type" => "varchar",
				"constraint" => "255"
			),
			"avatar" => array(
				"type" => "text"
			),
			"ip" => array(
				"type" => "varchar",
				"constraint" => "255"
			),
			"only_ip" => array(
				"type" => "int",
				"constraint" => "1"
			),
			"fb_login" => array(
				"type" => "varchar",
				"constraint" => "255"
			),
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key("id",TRUE);
		ee()->dbforge->create_table($this->table_name);
		
		mkdir( $_SERVER['DOCUMENT_ROOT']."/images/avatar", 0777);

		return TRUE;
	}



	public function uninstall(){

		ee()->db->where("snippet_name","global_auth_css");
		ee()->db->delete("snippets");

		$template_id = ee()->db->select('template_id')
				  ->from('templates')
				  ->where("template_name","edit")
				  ->get()->result_array();
		$template_id = $template_id[0]["template_id"];

		ee()->db->where("template_id",$template_id);
		ee()->db->delete("template_routes");

		$group_id = ee()->db->select('group_id')
				  ->from('template_groups')
				  ->where("group_name",$this->table_name)
				  ->get()->result_array();
		$group_id = $group_id[0]["group_id"];

		ee()->db->where("group_name",$this->table_name);
		ee()->db->delete("template_groups");

		ee()->db->where("group_id",$group_id);
		ee()->db->delete("templates");

		ee()->db->where("class",$this->module_name);
		ee()->db->delete("actions");

		ee()->db->where("module_name",$this->module_name);
		ee()->db->delete("modules");
		ee()->dbforge->drop_table($this->module_name);
		
		$folder = $_SERVER['DOCUMENT_ROOT']."/images/avatar";
	    $glob = glob($folder."/*");
	    foreach ($glob as $files) {
	    	$file = glob($files."/*");
		        foreach ($file as $f) {
		            unlink($f);
			    }
            rmdir($files);
	    }
		rmdir( $_SERVER['DOCUMENT_ROOT']."/images/avatar");

		unset($_SESSION["user_id"]);

		return TRUE;
	}

}