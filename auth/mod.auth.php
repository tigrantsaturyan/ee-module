<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

	protected $tag_vars;

	public function __construct(){
		@session_start();
		ee()->lang->loadfile('auth');
		ee()->load->helper(array('form', 'url'));
		ee()->load->library('form_validation');
	}

	/**
	*	Simple Form
	*/

	public function simple_form($action = null,$class = null, $extra_hidden = array()){
		if(empty($action)){
			$action = ee()->TMPL->fetch_param('type', 'no');
		}
		$data = array();
		$data['action'] = ee()->functions->create_url(ee()->uri->uri_string);

		if (ee()->TMPL->fetch_param('secure_action') == 'yes') {
			$data['action'] = str_replace('http://', 'https://', $data['action']);
		}

		$data['id'] = ee()->TMPL->fetch_param('form_id');
		$data['name'] = ee()->TMPL->fetch_param('form_name');
		$data["enctype"] = "multi";
		if(!empty($class)){
			$data["class"] = $class;
		}else{
			$data["class"] = "form-signin";
		}

		$data['hidden_fields'] = $extra_hidden;
		$data['hidden_fields']['ACT'] = ee()->functions->fetch_action_id(__CLASS__, $action);
		$data['hidden_fields']['return_url'] = ee()->TMPL->fetch_param('return');

		if ('PREVIOUS_URL' === $data['hidden_fields']['return_url']) {
			$data['hidden_fields']['return_url'] = $this->history(1);
		}
		// prevents errors in case there are no tag params
		ee()->TMPL->tagparams['encrypted_params'] = 1;

		// encrypt tag parameters
		ee()->load->library('encrypt');
		$data['hidden_fields']['_params'] = ee()->encrypt->encode(json_encode(ee()->TMPL->tagparams));

		return ee()->functions->form_declaration($data).
		ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $this->tag_vars).'</form>';
	}

	/**
	*	Login
	*/
	public function login(){
		if(!empty(ee()->input->post("email")) && !empty(ee()->input->post("password"))){
			$this->check_login();
		}else{
			ee()->form_validation->set_rules("email","E-mail","trim|required|valid_email");
			ee()->form_validation->set_rules("password","Password","trim|required");
			if(ee()->form_validation->run() == FALSE){
				$form_errors = array();
				foreach (array('email', 'password','all') as $field_name){
					$field_error = form_error($field_name);
					if ($field_error){
					    $form_errors[] = $field_error;
					    $this->tag_vars[0]['error:'.$field_name] = $field_error;
					}else{
					    $this->tag_vars[0]['error:'.$field_name] = "";
					}
				}
			}
		}
		return $this->simple_form("act_login");
	}

    /**
     * Login form action
     */
    public function act_login() {
        return ee()->core->generate_page();
    }

	/**
	*	Check login
	*/
	public function check_login(){
		$email = ee()->input->post("email");
		$password = md5(ee()->input->post("password"));

		$data = array(
			"login" => $email,
			"password" => $password
		);
		$id = ee()->db->select('id')
				  ->from('auth')
				  ->where($data)
				  ->get()->result_array();
	  	if(empty($id)){
			$this->tag_vars[0] = array(
			    'error:all' => "<span class='error'>*Invalid E-mail or Password</span>",
			    'error:email' => '',
			    'error:password' => ''
			);
	  	}else{
	  		$_SESSION["user_id"] = $id[0]["id"];
	  		return ee()->functions->redirect("auth/account");
	  	}
	}

	/**
	*	Register a new member
	*/
	public function register(){

		if(!empty(ee()->input->post("add_member"))){
			$this->check_register();
		}else{
			$this->tag_vars[0] = array(
			    'error:first_name' => '',
			    'error:last_name' => '',
			    'error:birthday' => '',
			    'error:email' => '',
			    'error:password' => '',
			    'error:confirm_password' => '',
			    'error:avatar' => ''
			);
		}
		return $this->simple_form("act_register");
	}


    /**
     * Register form action
     */
    public function act_register() {
        return ee()->core->generate_page();
    }

	/**
	*	Check register
	*/
	public function check_register(){
		$data["last_name"] = ee()->input->post("last_name");
		$data["first_name"] = ee()->input->post("first_name");
		$data["birthday"] = ee()->input->post("birthday");
		$data["login"] = ee()->input->post("email");
		$data["password"] = md5(ee()->input->post("password"));
		$data["ip"] = $_SERVER["REMOTE_ADDR"];

		ee()->form_validation->set_rules("first_name","First Name","trim|required|min_length[5]|max_length[15]");
		ee()->form_validation->set_rules("last_name","Last Name","trim|required|min_length[5]|max_length[15]");
		ee()->form_validation->set_rules("birthday","Birthday","trim|required");
		ee()->form_validation->set_rules("email","E-mail","trim|required|min_length[5]|max_length[55]|valid_email");
		ee()->form_validation->set_rules("password","Password","trim|required|min_length[5]|max_length[55]");
		ee()->form_validation->set_rules("confirm_password","Confirm Password","trim|required|min_length[5]|max_length[55]|matches[password]");

		if(ee()->form_validation->run() == FALSE){
			$form_errors = array();
			foreach (array('first_name', 'last_name', 'birthday', 'email', 'password', 'confirm_password','avatar') as $field_name){
				$field_error = form_error($field_name);
				if ($field_error){
				    $form_errors[] = $field_error;
				    $this->tag_vars[0]['error:'.$field_name] = $field_error;
				}else{
				    $this->tag_vars[0]['error:'.$field_name] = "";
				}
			}

			$array_type = array("image/gif","image/jpeg","image/png");

			if(!empty($_FILES["avatar"]["name"]) && !in_array($_FILES["avatar"]["type"], $array_type)){
				$this->tag_vars[0]['error:avatar'] = "Upload Failed! The file has an invalid extension (jpg,png,gif)";
			}else{
				$this->tag_vars[0]['error:avatar'] = "The Avatar field is required.";
			}
		}else{
			$data["avatar"] = $this->uploadImage("avatar");
			ee()->db->insert("auth",$data);

			$_SESSION["user_id"] = ee()->db->insert_id();

	  		return ee()->functions->redirect("index.php/auth/account");
		}
	}

	/**
	*	Upload Avatar
	*/

	public function uploadImage($field){
		$id = ee()->db->query("SELECT `id` FROM `exp_auth` ORDER BY `id` desc LIMIT 1")->result();
		if(isset($_SESSION["user_id"])){
			$config['upload_path'] = "./images/avatar/" . $_SESSION["user_id"];
		}elseif(!empty($id)){
			$id = $id[0]->id;
			$id = $id + 1;
			mkdir( $_SERVER['DOCUMENT_ROOT']."/images/avatar/" . $id, 0777);
			$config['upload_path'] = "./images/avatar/" . $id ;
		}else{
			mkdir( $_SERVER['DOCUMENT_ROOT']."/images/avatar/1", 0777);
			$config['upload_path'] = "./images/avatar/1";
		}

		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1000000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$config['create_thumb']  = TRUE;
		ee()->load->library('upload',$config);
		ee()->upload->do_upload($field);
		$data_upload = ee()->upload->data();

		return $data_upload["file_name"];

	}

	/**
	* Logout
	*/

	public function logout(){
		unset($_SESSION["user_id"]);
	}

	/**
	* Get Member Information
	*/

	public function getUser(){
		$user_id = $_SESSION["user_id"];

		$user_information = ee()->db->select('*')
				  ->from('auth')
				  ->where("id",$user_id)
				  ->get()->result_array();
		
		$data["user_id"] = $user_id;
		$data["img"] =  "/images/avatar/" . $user_id . "/" . $user_information[0]["avatar"];
		$data["full_name"] = $user_information[0]["first_name"] . " " . $user_information[0]["last_name"];
		$data["first_name"] = $user_information[0]["first_name"];
		$data["last_name"] = $user_information[0]["last_name"];
		$data["birthday"] = $user_information[0]["birthday"];
		$data["login"] = $user_information[0]["login"];

		return ee()->load->view('account',$data,TRUE);
	}

	/**
	*	Edit Profile
	*/
	public function editProfile(){

		$user_id = $_SESSION["user_id"];
		$user_information = ee()->db->select('*')
				  ->from('auth')
				  ->where("id",$user_id)
				  ->get()->result_array();

		if(!empty(ee()->input->post("save_edit"))){
			$this->check_edit_profile();
		}else{
			$this->tag_vars[0] = array(
			    'error:first_name' => '',
			    'error:last_name' => '',
			    'error:birthday' => '',
			    'error:login' => '',
			    'error:password' => '',
			    'error:avatar' => '',
				"first_name" => $user_information[0]["first_name"],
				"last_name" => $user_information[0]["last_name"],
				"birthday" => $user_information[0]["birthday"],
				"login" => $user_information[0]["login"],
				"password" => $user_information[0]["password"],
				"avatar" => "/images/avatar/" . $user_id . "/" . $user_information[0]["avatar"]
			);
		}

		return $this->simple_form("act_editProfileSave","form-horizontal");
	}

    /**
     * Edit Profile form action
     */
    public function act_editProfileSave() {
        return ee()->core->generate_page();
    }

	/**
	*	Check edit profile
	*/
	public function check_edit_profile(){
		$user_id = $_SESSION["user_id"];
		$data["last_name"] = ee()->input->post("last_name");
		$data["first_name"] = ee()->input->post("first_name");
		$data["birthday"] = ee()->input->post("birthday");
		$data["login"] = ee()->input->post("login");
		$data["password"] = md5(ee()->input->post("password"));
		$data["ip"] = $_SERVER["REMOTE_ADDR"];

		ee()->form_validation->set_rules("first_name","First Name","trim|required|min_length[5]|max_length[15]");
		ee()->form_validation->set_rules("last_name","Last Name","trim|required|min_length[5]|max_length[15]");
		ee()->form_validation->set_rules("birthday","Birthday","trim|required");
		ee()->form_validation->set_rules("login","E-mail","trim|min_length[5]|max_length[55]|valid_email");
		ee()->form_validation->set_rules("password","Password","trim|min_length[5]|max_length[55]");

		$array_type = array("image/gif","image/jpeg","image/png");

		if(ee()->form_validation->run() == FALSE){
			$form_errors = array();
			foreach (array('first_name', 'last_name', 'birthday', 'login', 'password','avatar') as $field_name){
				$field_error = form_error($field_name);
				if ($field_error){
				    $form_errors[] = $field_error;
				    $this->tag_vars[0]['error:'.$field_name] = $field_error;
				}else{
				    $this->tag_vars[0]['error:'.$field_name] = "";
				}
				if($field_name !== "avatar"){
				    $this->tag_vars[0][$field_name] = $data[$field_name];
				}
			}

			if(!empty($_FILES["avatar"]["name"]) && !in_array($_FILES["avatar"]["type"], $array_type)){
				$this->tag_vars[0]['error:avatar'] = "Upload Failed! The file has an invalid extension (jpg,png,gif)";
			}
		}else{
			if(!empty($_FILES["avatar"]["name"])){	
				$data["avatar"] = $this->uploadImage("avatar");
			}
			ee()->db->where('id', $user_id);
			ee()->db->update("auth",$data);

	  		return ee()->functions->redirect("../");
		}
	}

}