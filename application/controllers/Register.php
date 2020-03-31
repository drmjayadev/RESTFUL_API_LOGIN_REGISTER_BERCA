<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
date_default_timezone_set('asia/jakarta');

class Register extends REST_Controller {
    
	function __construct(){
        parent::__construct();
	   $this->load->database();
        $this->load->model('user');
	}
	
	public function index_get(){
		$this->response([
				'status' => false,
				'message' => 'Index Register',
			], REST_Controller::HTTP_OK);
	}

	public function index_post(){

		$key           = $this->input->post('key');
		$username      = $this->input->post('username');
		$password      = $this->input->post('password');
		$email         = $this->input->post('email');

		$password_hash = $this->bcrypt->hash_password($password);
		$token         = $this->bcrypt->hash_password(rand(10,100));
		$status        = 1;
		$level         = 1;
		$kunci         = "b3rc4";

		if($key==$kunci){

			if($username != "" && $password != "" && $email != "" && $key != ""){

				$getUser = $this->user->cekUserEmail($username,$email)->row();
				if($getUser){
					$this->response([
						'status' => 404,
						'message' => 'User or Email already exist',
					], REST_Controller::HTTP_OK); 
				}else{
					$where = array(
						'username'  => $username,
						'password'  => $password_hash,
						'email'     => $email,
						'token'     => $token,
						'status'    => $status,
						'level'    => $level
					);

					$saveUser = $this->user->saveUser($where);
					if(isset($saveUser)){
						$this->response([
							'status' => 200,
							'message' => 'Register Success',
							'data' => $where,
						], REST_Controller::HTTP_OK); 
					}else{
						$this->response([
							'status' => 404,
							'message' => 'Register Failed',
						], REST_Controller::HTTP_OK); 			
					}
				}


			}else{
				$this->response([
					'status' => 404,
					'message' => 'Please Input fields',
				], REST_Controller::HTTP_OK); 
			}

		}else{
			$this->response([
				'status' => 404,
				'message' => 'Invalid Key',
			], REST_Controller::HTTP_OK); 
		}


	

	}




}
